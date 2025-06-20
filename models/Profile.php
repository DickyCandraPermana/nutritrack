<?php

namespace Models;

use PDO, PDOException, Exception;

require_once 'config/helpers.php';

class Profile
{
  private $db;

  /**
   * Constructor for Profile model.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct($db)
  {
    $this->db = $db;
  }

  /**
   * Retrieves user data by user ID, including calculated BMI, BMR, and TDEE.
   *
   * @param int $id The ID of the user.
   * @return array An associative array containing user data.
   */
  public function getUserById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$id]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $data["umur"] = hitungUmur($data["tanggal_lahir"]);
    if ($data["tinggi_badan"] != null && $data["berat_badan"] != null) {
      $data["bmi"] = hitungBMI($data["berat_badan"], $data["tinggi_badan"]);
      $data["bmi_status"] = $data["bmi"] < 18.5 ? 'Kurus' : ($data["bmi"] < 25 ? 'Normal' : 'Gemuk');
      $data["bmr"] = hitungBMR($data["berat_badan"], $data["tinggi_badan"], $data["umur"], $data["jenis_kelamin"]);
      $data["tdee"] = hitungTDEE($data["bmr"], $data["aktivitas"]);
    }

    return $data;
  }

  /**
   * Retrieves weekly nutrition data for a specific user.
   *
   * @param int $userId The ID of the user.
   * @return array An associative array containing weekly calorie, protein, carbs, and fat data.
   */
  public function getNutritionInWeekData($userId)
  {
    $today = date('Y-m-d');
    $sevenDaysAgo = date('Y-m-d', strtotime('-6 days'));

    $stmt = $this->db->prepare(
      "SELECT 
      registrasi_makanan.tanggal, 
      nutrisi.nutrition_id, 
      SUM(detail_nutrisi_makanan.jumlah) AS jumlah
    FROM registrasi_makanan 
    INNER JOIN detail_registrasi_makanan ON registrasi_makanan.reg_id = detail_registrasi_makanan.reg_id
    INNER JOIN detail_nutrisi_makanan ON detail_registrasi_makanan.food_id = detail_nutrisi_makanan.food_id 
    INNER JOIN nutrisi ON detail_nutrisi_makanan.nutrition_id = nutrisi.nutrition_id 
    WHERE registrasi_makanan.user_id = ?
      AND nutrisi.nutrition_id IN (1, 2, 6, 8)
      AND registrasi_makanan.tanggal BETWEEN ? AND ?
    GROUP BY registrasi_makanan.tanggal, nutrisi.nutrition_id
    ORDER BY registrasi_makanan.tanggal ASC;"
    );
    $stmt->execute([$userId, $sevenDaysAgo, $today]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inisialisasi tanggal 7 hari ke belakang (index 0 = 6 hari lalu, index 6 = hari ini)
    $tanggalList = [];
    for ($i = 6; $i >= 0; $i--) {
      $tanggal = date('Y-m-d', strtotime("-$i days"));
      $tanggalList[$tanggal] = 6 - $i; // misal 2025-04-12 → index 0, 2025-04-18 → index 6
    }

    // Inisialisasi array nutrisi
    $data = [
      'calories' => array_fill(0, 7, 0),
      'protein'  => array_fill(0, 7, 0),
      'carbs'    => array_fill(0, 7, 0),
      'fat'      => array_fill(0, 7, 0),
    ];

    // Mapping nutrition_id → nama array
    $map = [
      1 => 'calories',
      8 => 'protein',
      6 => 'carbs',
      2 => 'fat'
    ];

    foreach ($results as $row) {
      $tgl = $row['tanggal'];
      $id = (int) $row['nutrition_id'];
      $jumlah = (float) $row['jumlah'];

      if (!isset($map[$id])) continue;
      if (!isset($tanggalList[$tgl])) continue;

      $indexHari = $tanggalList[$tgl];
      $key = $map[$id];

      $data[$key][$indexHari] += $jumlah;
    }

    return $data;
  }

  /**
   * Retrieves detailed food registration data for a specific user.
   *
   * @param int $id The ID of the user.
   * @return array An associative array of food registration details, grouped by meal.
   */
  public function getDetailRegistrasiMakanan($id)
  {
    $stmt = $this->db->prepare(
      "SELECT 
            detail_registrasi_makanan.id,
            makanan.nama_makanan, 
            makanan.deskripsi, 
            registrasi_makanan.tanggal, 
            detail_registrasi_makanan.waktu_makan, 
            detail_registrasi_makanan.jumlah_porsi, 
            detail_registrasi_makanan.satuan,
            detail_nutrisi_makanan.jumlah, 
            detail_nutrisi_makanan.satuan AS nutrisi_satuan,
            nutrisi.nama AS nutrisi_nama
        FROM detail_registrasi_makanan
        INNER JOIN registrasi_makanan ON detail_registrasi_makanan.reg_id = registrasi_makanan.reg_id
        INNER JOIN makanan ON detail_registrasi_makanan.food_id = makanan.food_id
        INNER JOIN detail_nutrisi_makanan ON makanan.food_id = detail_nutrisi_makanan.food_id
        INNER JOIN nutrisi ON detail_nutrisi_makanan.nutrition_id = nutrisi.nutrition_id
        WHERE registrasi_makanan.user_id = ?"
    );
    $stmt->execute([$id]);

    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $makananId = $row['id'];

      if (!isset($data[$makananId])) {
        // Simpan data makanan tanpa nutrisi dulu
        $data[$makananId] = [
          'nama_makanan' => $row['nama_makanan'],
          'deskripsi' => $row['deskripsi'],
          'tanggal' => $row['tanggal'],
          'waktu_makan' => $row['waktu_makan'],
          'jumlah_porsi' => $row['jumlah_porsi'],
          'satuan' => $row['satuan'],
          'nutrisi' => [] // Buat array kosong untuk nutrisi
        ];
      }

      // Tambahkan nutrisi dalam format array 2 dimensi
      $data[$makananId]['nutrisi'][] = [
        $row['nutrisi_nama'],
        $row['jumlah'],
        $row['nutrisi_satuan']
      ];
    }

    return $data;
  }

  /**
   * Updates a user's profile information.
   *
   * @param array $data An associative array containing user profile data.
   * @return bool True if the profile was updated, false otherwise.
   */
  public function updateProfile($data)
  {
    // Ensure user_id is present
    if (!isset($data['user_id'])) {
      // This should ideally be handled by the controller with a proper error response
      return false;
    }


    // Use null coalescing operator to safely access data and provide defaults
    $username = $data['username'] ?? null;
    $bio = $data['bio'] ?? null;
    $first_name = $data['first_name'] ?? null;
    $last_name = $data['last_name'] ?? null;
    $email = $data['email'] ?? null;
    $phone_number = $data['phone_number'] ?? null;
    $jenis_kelamin = $data['jenis_kelamin'] ?? null;
    $tanggal_lahir = $data['tanggal_lahir'] ?? null;
    $tinggi_badan = (float)($data['tinggi_badan'] ?? 0); // Cast to float, default to 0
    $berat_badan = (float)($data['berat_badan'] ?? 0);   // Cast to float, default to 0
    $aktivitas = $data['aktivitas'] ?? null;
    $user_id = $data['user_id'];

    $umur = hitungUmur($tanggal_lahir); // hitungUmur already handles null/empty

    $sql = "UPDATE users SET username = ?, bio = ?, first_name = ?, last_name = ?, email = ?, phone_number = ?, jenis_kelamin = ?, tanggal_lahir = ?, tinggi_badan = ?, berat_badan = ?, aktivitas = ? ";
    $params = [
      $username,
      $bio,
      $first_name,
      $last_name,
      $email,
      $phone_number,
      $jenis_kelamin,
      $tanggal_lahir,
      $tinggi_badan,
      $berat_badan,
      $aktivitas
    ];

    // Conditionally add profile_picture if it exists in data
    if (isset($data['profile_picture'])) {
      $sql .= ", profile_picture = ? ";
      $params[] = $data['profile_picture'];
    }

    $sql .= "WHERE user_id = ?";
    $params[] = $user_id;

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    $updated = $stmt->rowCount() > 0;

    // Update session data - ensure these calculations also use the safely accessed variables
    $_SESSION['bmi'] = hitungBMI($berat_badan, $tinggi_badan);
    $_SESSION['bmi_status'] = $_SESSION['bmi'] < 18.5 ? 'Kurus' : ($_SESSION['bmi'] < 25 ? 'Normal' : 'Gemuk');

    $_SESSION['bmr'] = hitungBMR($berat_badan, $tinggi_badan, $umur, $jenis_kelamin);

    $_SESSION['tdee'] = hitungTDEE($_SESSION['bmr'], $aktivitas);

    return $updated;
  }

  /**
   * Checks if a food registration entry exists for a user on the current date.
   *
   * @param int $id The ID of the user.
   * @return int The number of rows found (0 or 1).
   */
  public function cekRegistrasiMakanan($id)
  {
    $user_id = $id;
    $tanggal = getCurrentDate();
    $stmt = $this->db->prepare("SELECT * FROM registrasi_makanan WHERE user_id = ? AND tanggal = ?");
    $stmt->execute([$user_id, $tanggal]);
    return $stmt->rowCount();
  }

  /**
   * Adds a new food registration entry for a user on the current date.
   *
   * @param int $id The ID of the user.
   * @return void
   */
  public function tambahRegistrasiMakanan($id)
  {
    $user_id = $id;
    $tanggal = getCurrentDate();
    $stmt = $this->db->prepare("INSERT INTO registrasi_makanan (user_id, tanggal) VALUES (?, ?)");
    $stmt->execute([$user_id, $tanggal]);
  }

  /**
   * Retrieves the food registration entry for a user on the current date.
   *
   * @param int $id The ID of the user.
   * @return array|false An associative array representing the registration entry, or false if not found.
   */
  public function getRegistrasiMakanan($id)
  {
    $user_id = $id;
    $tanggal = getCurrentDate();
    $stmt = $this->db->prepare("SELECT * FROM registrasi_makanan WHERE user_id = ? AND tanggal = ?");
    $stmt->execute([$user_id, $tanggal]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Adds a new detailed food registration entry for a user.
   * Creates a new main registration entry if one doesn't exist for the current date.
   *
   * @param array $data An associative array containing food_id, waktu_makan, jumlah_porsi, satuan, and optional catatan.
   * @return void
   */
  public function tambahDetailRegistrasiMakanan($data)
  {
    $user_id = $data['user_id'];
    $makanan = $data['food_id'];
    $jam = $data['waktu_makan'];
    $jumlah_porsi = $data['jumlah_porsi'];
    $satuan = $data['satuan'];
    $catatan = $data['catatan'];

    if (!isset($catatan)) {
      $catatan = null;
    }

    if ($this->cekRegistrasiMakanan($user_id) == 0) {
      $this->tambahRegistrasiMakanan($user_id);
    }

    $reg_id = $this->getRegistrasiMakanan($user_id)['reg_id'];

    $stmt = $this->db->prepare("INSERT INTO detail_registrasi_makanan (reg_id, food_id, waktu_makan, jumlah_porsi, satuan, catatan) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$reg_id, $makanan, $jam, $jumlah_porsi, $satuan, $catatan]);
  }

  /**
   * Retrieves the registration ID for a user on a specific date.
   *
   * @param int $user_id The ID of the user.
   * @param string $tanggal The date in 'YYYY-MM-DD' format.
   * @return int|null The registration ID, or null if not found.
   */
  public function getRegIdByUserIdTanggal($user_id, $tanggal)
  {
    $stmt = $this->db->prepare("SELECT registrasi_makanan.reg_id FROM registrasi_makanan 
    WHERE registrasi_makanan.user_id = ? AND tanggal = ?");
    $stmt->execute([$user_id, $tanggal]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($data) == 0) return null;
    return $data[0]['reg_id'];
  }

  /**
   * Retrieves consumed food data for a user on a specific date, grouped by meal time.
   *
   * @param int $id The ID of the user.
   * @param string $tanggal The date in 'YYYY-MM-DD' format.
   * @return array|null An array of consumed food data, grouped by meal, or null if no data found.
   */
  public function getConsumedFoodData($id, $tanggal)
  {
    $reg_id = $this->getRegIdByUserIdTanggal($id, $tanggal);

    if ($reg_id == null) {
      return null;
    }

    $stmt = $this->db->prepare(
      "SELECT makanan.nama_makanan, detail_registrasi_makanan.jumlah_porsi, detail_registrasi_makanan.satuan, detail_registrasi_makanan.waktu_makan, detail_nutrisi_makanan.jumlah, detail_nutrisi_makanan.nutrition_id 
      FROM detail_registrasi_makanan 
      INNER JOIN makanan ON detail_registrasi_makanan.food_id = makanan.food_id
      INNER JOIN detail_nutrisi_makanan ON makanan.food_id = detail_nutrisi_makanan.food_id
      INNER JOIN nutrisi ON detail_nutrisi_makanan.nutrition_id = nutrisi.nutrition_id
      WHERE reg_id = ? AND nutrisi.nutrition_id IN (1, 2, 6, 8, 9)
      ORDER BY waktu_makan ASC"
    ); // 1 = kalori, 2 = karbohidrat, 6 = protein, 8 = lemak
    $stmt->execute([$reg_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $meals = [];

    foreach ($rows as $row) {
      $waktu = $row['waktu_makan'];
      $nama = $row['nama_makanan'];
      $key = "{$waktu}_{$nama}";

      // Initialize meal slot
      if (!isset($meals[$waktu])) {
        $meals[$waktu] = [
          'name' => $waktu,
          'totalCalories' => 0,
          'items' => []
        ];
      }

      // Initialize item if belum ada
      if (!isset($meals[$waktu]['items'][$key])) {
        $meals[$waktu]['items'][$key] = [
          'name' => $nama,
          'portion' => "{$row['jumlah_porsi']} {$row['satuan']}",
          'calories' => 0,
          'carbs' => 0,
          'protein' => 0,
          'fat' => 0
        ];
      }

      // Map nutrition_id ke field
      switch ((int)$row['nutrition_id']) {
        case 1:
          $meals[$waktu]['items'][$key]['calories'] = (float)$row['jumlah'];
          $meals[$waktu]['totalCalories'] += (float)$row['jumlah'];
          break;
        case 2:
          $meals[$waktu]['items'][$key]['fat'] = (float)$row['jumlah'];
          break;
        case 6:
          $meals[$waktu]['items'][$key]['carbs'] = (float)$row['jumlah'];
          break;
        case 8:
          $meals[$waktu]['items'][$key]['protein'] = (float)$row['jumlah'];
          break;
      }
    }

    // Rapihin jadi array indexed (items-nya juga)
    $result = [];
    foreach ($meals as $meal) {
      $meal['items'] = array_values($meal['items']);
      $result[] = $meal;
    }

    return $result;
  }

  /**
   * Retrieves tracked nutrient totals for a user on a specific date.
   *
   * @param int $id The ID of the user.
   * @param string $tanggal The date in 'YYYY-MM-DD' format.
   * @return array|null An associative array of total nutrients (e.g., 'calories', 'protein'), or null if no data found.
   */
  public function getTrackedNutrient($id, $tanggal)
  {
    $reg_id = $this->getRegIdByUserIdTanggal($id, $tanggal);

    if ($reg_id == null) {
      return null;
    }

    $stmt = $this->db->prepare(
      "SELECT SUM(detail_nutrisi_makanan.jumlah) AS 'total_nutrisi', nutrisi.nama FROM detail_registrasi_makanan
      INNER JOIN detail_nutrisi_makanan ON detail_registrasi_makanan.food_id = detail_nutrisi_makanan.food_id
      INNER JOIN nutrisi ON detail_nutrisi_makanan.nutrition_id = nutrisi.nutrition_id
      WHERE reg_id = ? AND nutrisi.nutrition_id IN (1, 2, 6, 8, 9)
      GROUP BY detail_nutrisi_makanan.nutrition_id"
    );
    $stmt->execute([$reg_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];

    foreach ($rows as $row) {
      $result[$row['nama']] = $row['total_nutrisi'];
    }


    return $result;
  }

  /**
   * Retrieves the total calories consumed by a user on the current date.
   *
   * @param int $id The ID of the user.
   * @return float|null The total calories, or null if no data found.
   */
  public function getTotalCalories($id)
  {
    $reg_id = $this->getRegistrasiMakanan($id);
    if (!$reg_id) {
      return null;
    } else {
      $reg_id = $reg_id['reg_id'];
    }
    $stmt = $this->db->prepare(
      "SELECT SUM(detail_nutrisi_makanan.jumlah) AS 'total_calories' FROM detail_registrasi_makanan 
      INNER JOIN makanan ON detail_registrasi_makanan.food_id = makanan.food_id
      INNER JOIN detail_nutrisi_makanan ON makanan.food_id = detail_nutrisi_makanan.food_id
      INNER JOIN nutrisi ON detail_nutrisi_makanan.nutrition_id = nutrisi.nutrition_id
      WHERE reg_id = ? AND nutrisi.nutrition_id = 1"
    );
    $stmt->execute([$reg_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    return $row['total_calories'];
  }

  public function addReminder($data)
  {
    try {
      $stmt = $this->db->prepare("INSERT INTO user_reminder (user_id, judul, waktu) VALUES (?, ?, ?)");
      $stmt->execute([$data['user_id'], $data['judul'], $data['waktu']]);

      return $this->db->lastInsertId();
    } catch (PDOException $e) {
      return [
        'error' => $e->getMessage()
      ];
    }
  }

  public function getUserReminder($user_id)
  {
    $stmt = $this->db->prepare("SELECT * FROM user_reminder WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function deleteReminder($reminder_id)
  {
    try {
      $stmt = $this->db->prepare("DELETE FROM user_reminder WHERE id_reminder = ?");
      $stmt->execute([$reminder_id]);
      return true;
    } catch (PDOException $e) {
      return [
        'error' => $e->getMessage()
      ];
    }
  }

  public function completeReminder($reminder_id)
  {
    try {
      $stmt = $this->db->prepare("UPDATE user_reminder SET completed = 1 WHERE id_reminder = ?");
      $stmt->execute([$reminder_id]);
      return true;
    } catch (PDOException $e) {
      return [
        'error' => $e->getMessage()
      ];
    }
  }

  public function tambahAir($user_id, $tanggal, $jumlah, $tipe = "tambah")
  {
    try {
      // Ambil air_dikonsumsi sekarang
      $stmt = $this->db->prepare("SELECT air_dikonsumsi FROM registrasi_makanan WHERE user_id = ? AND tanggal = ?");
      $stmt->execute([$user_id, $tanggal]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // Default kalau belum ada record
      $currentWater = $row ? (int)$row['air_dikonsumsi'] : 0;

      // Tambah atau kurangin
      if ($tipe == "tambah") {
        $currentWater += $jumlah;
      } else {
        $currentWater -= $jumlah;
        if ($currentWater < 0) $currentWater = 0; // Biar gak minus
      }

      // Update ke DB
      $stmt = $this->db->prepare("UPDATE registrasi_makanan SET air_dikonsumsi = ? WHERE user_id = ? AND tanggal = ?");
      $stmt->execute([$currentWater, $user_id, $tanggal]);

      return true;
    } catch (PDOException $e) {
      return [
        'error' => $e->getMessage()
      ];
    }
  }
}

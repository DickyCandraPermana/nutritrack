<?php

namespace Models;

use PDO, PDOException, Exception;

require_once 'config/helpers.php';

class Profile
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

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

  public function updateProfile($data)
  {
    extract($data);
    $umur = hitungUmur($tanggal_lahir);

    $stmt = $this->db->prepare("UPDATE users SET username = ?, bio = ?, profile_picture = ?, first_name = ?, last_name = ?, email = ?, phone_number = ?, jenis_kelamin = ?, tanggal_lahir = ?, tinggi_badan = ?, berat_badan = ?, aktivitas = ? WHERE user_id = ?");

    $stmt->execute([$username, $bio, $profile_picture, $first_name, $last_name, $email, $phone_number, $jenis_kelamin, $tanggal_lahir, $tinggi_badan, $berat_badan, $aktivitas, $user_id]);

    if ($stmt->rowCount() > 0) {
      echo "Update berhasil!";
    } else {
      echo "Gak ada perubahan data.";
    }

    $_SESSION['bmi'] = hitungBMI($data['berat_badan'], $data['tinggi_badan']);
    $_SESSION['bmi_status'] = $_SESSION['bmi'] < 18.5 ? 'Kurus' : ($_SESSION['bmi'] < 25 ? 'Normal' : 'Gemuk');

    $_SESSION['bmr'] = hitungBMR($data['berat_badan'], $data['tinggi_badan'], $umur, $data['jenis_kelamin']);

    $_SESSION['tdee'] = hitungTDEE($_SESSION['bmr'], $data['aktivitas']);
  }

  public function cekRegistrasiMakanan($id)
  {
    $user_id = $id;
    $tanggal = getCurrentDate();
    $stmt = $this->db->prepare("SELECT * FROM registrasi_makanan WHERE user_id = ? AND tanggal = ?");
    $stmt->execute([$user_id, $tanggal]);
    return $stmt->rowCount();
  }

  public function tambahRegistrasiMakanan($id)
  {
    $user_id = $id;
    $tanggal = getCurrentDate();
    $stmt = $this->db->prepare("INSERT INTO registrasi_makanan (user_id, tanggal) VALUES (?, ?)");
    $stmt->execute([$user_id, $tanggal]);
  }

  public function getRegistrasiMakanan($id)
  {
    $user_id = $id;
    $tanggal = getCurrentDate();
    $stmt = $this->db->prepare("SELECT * FROM registrasi_makanan WHERE user_id = ? AND tanggal = ?");
    $stmt->execute([$user_id, $tanggal]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

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

  public function getRegIdByUserIdTanggal($user_id, $tanggal)
  {
    $stmt = $this->db->prepare("SELECT registrasi_makanan.reg_id FROM registrasi_makanan 
    WHERE registrasi_makanan.user_id = ? AND tanggal = ?");
    $stmt->execute([$user_id, $tanggal]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($data) == 0) return null;
    return $data[0]['reg_id'];
  }

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
          // case 9 => fiber (serat) tidak digunakan di output
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
}

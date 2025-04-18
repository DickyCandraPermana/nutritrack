<?php
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

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getNutritionInWeekData($userId)
  {
    $today = date('Y-m-d');
    $sevenDaysAgo = date('Y-m-d', strtotime('-6 days'));

    $stmt = $this->db->prepare(
      "SELECT 
      user_makanan.tanggal, 
      nutrisi.nutrition_id, 
      SUM(makanan_nutrisi.jumlah) AS jumlah
    FROM user_makanan 
    INNER JOIN makanan ON user_makanan.food_id = makanan.food_id 
    INNER JOIN makanan_nutrisi ON makanan.food_id = makanan_nutrisi.food_id 
    INNER JOIN nutrisi ON makanan_nutrisi.nutrition_id = nutrisi.nutrition_id 
    WHERE user_makanan.user_id = ?
      AND nutrisi.nutrition_id IN (1, 2, 6, 8)
      AND user_makanan.tanggal BETWEEN ? AND ?
    GROUP BY user_makanan.tanggal, nutrisi.nutrition_id
    ORDER BY user_makanan.tanggal ASC;"
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




  public function getUserMakananByUserId($user_id)
  {
    $stmt = $this->db->prepare("SELECT * FROM user_makanan WHERE user_id = ? ORDER BY tanggal DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getUserMakananByDate($date)
  {
    $user_id = $_SESSION['user_id'];
    $stmt = $this->db->prepare("SELECT * FROM user_makanan WHERE user_id = ? AND tanggal = ?");
    $stmt->execute([$user_id, $date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getUserMakananById($id)
  {
    $stmt = $this->db->prepare(
      "SELECT 
            user_makanan.id,
            makanan.nama_makanan, 
            makanan.deskripsi, 
            user_makanan.tanggal, 
            user_makanan.waktu_makan, 
            user_makanan.jumlah_porsi, 
            user_makanan.satuan,
            makanan_nutrisi.jumlah, 
            makanan_nutrisi.satuan AS nutrisi_satuan,
            nutrisi.nama AS nutrisi_nama
        FROM user_makanan
        INNER JOIN makanan ON user_makanan.food_id = makanan.food_id
        INNER JOIN makanan_nutrisi ON makanan.food_id = makanan_nutrisi.food_id
        INNER JOIN nutrisi ON makanan_nutrisi.nutrition_id = nutrisi.nutrition_id
        WHERE user_makanan.user_id = ?"
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

  public function tambahMakanan($data)
  {
    $user_id = $data['user_id'];
    $makanan = $data['food_id'];
    $tanggal = getCurrentDate();
    $jam = $data['waktu_makan'];
    $jumlah_porsi = $data['jumlah_porsi'];
    $satuan = $data['satuan'];
    $catatan = $data['catatan'];

    $stmt = $this->db->prepare("INSERT INTO user_makanan (user_id, food_id, tanggal, waktu_makan, jumlah_porsi, satuan, catatan) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $makanan, $tanggal, $jam, $jumlah_porsi, $satuan, $catatan]);
  }

  public function editMakanan($data)
  {
    extract($data);
    $tanggal = getCurrentDate();
    $jam = getCurrentTime();

    $stmt = $this->db->prepare("UPDATE user_makanan SET food_id = ?, tanggal = ?, waktu_makan = ?, jumlah_porsi = ?, satuan = ?, catatan = ? WHERE user_makanan_id = ? AND user_id = ?");
    $stmt->execute([$food_id, $tanggal, $jam, $jumlah_porsi, $satuan, $catatan, $id, $user_id]);
  }

  public function deleteMakanan($id)
  {
    $stmt = $this->db->prepare("DELETE FROM user_makanan WHERE user_makanan_id = ?");
    $stmt->execute([$id]);
  }
}

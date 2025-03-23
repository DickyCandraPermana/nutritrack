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
    $user_id = $data['user_id'];
    $username = $data['username'];
    $bio = $data['bio'];
    $profile_picture = $data['profile_picture'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $phone_number = $data['phone_number'];
    $jenis_kelamin = $data['jenis_kelamin'];
    $tanggal_lahir = $data['tanggal_lahir'];
    $tinggi_badan = $data['tinggi_badan'];
    $berat_badan = $data['berat_badan'];
    $aktivitas = $data['aktivitas'];
    $umur = hitungUmur($data['tanggal_lahir']);

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
    $jam = getCurrentTime();
    $jumlah_porsi = $data['jumlah_porsi'];
    $satuan = $data['satuan'];
    $catatan = $data['catatan'];

    $stmt = $this->db->prepare("INSERT INTO user_makanan (user_id, food_id, tanggal, waktu_makan, jumlah_porsi, satuan, catatan) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $makanan, $tanggal, $jam, $jumlah_porsi, $satuan, $catatan]);
  }

  public function editMakanan($data)
  {
    $id = $data['id'];
    $user_id = $data['user_id'];
    $makanan = $data['food_id'];
    $tanggal = getCurrentDate();
    $jam = getCurrentTime();
    $jumlah_porsi = $data['jumlah_porsi'];
    $satuan = $data['satuan'];
    $catatan = $data['catatan'];

    $stmt = $this->db->prepare("UPDATE user_makanan SET food_id = ?, tanggal = ?, waktu_makan = ?, jumlah_porsi = ?, satuan = ?, catatan = ? WHERE user_makanan_id = ? AND user_id = ?");
    $stmt->execute([$makanan, $tanggal, $jam, $jumlah_porsi, $satuan, $catatan, $id, $user_id]);
  }

  public function deleteMakanan($id)
  {
    $stmt = $this->db->prepare("DELETE FROM user_makanan WHERE user_makanan_id = ?");
    $stmt->execute([$id]);
  }
}

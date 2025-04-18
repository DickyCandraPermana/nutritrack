<?php
function hitungBMI($berat, $tinggi)
{
  $tinggi_m = $tinggi / 100;
  return $berat / ($tinggi_m * $tinggi_m);
}

function hitungBMR($berat, $tinggi, $umur, $jenis_kelamin)
{
  if ($jenis_kelamin == 'Laki-laki') {
    return 88.36 + (13.4 * $berat) + (4.8 * $tinggi) - (5.7 * $umur);
  } else {
    return 447.6 + (9.2 * $berat) + (3.1 * $tinggi) - (4.3 * $umur);
  }
}

function hitungTDEE($bmr, $aktivitas)
{
  $faktor_aktivitas = [
    'sangat ringan' => 1.2,
    'ringan' => 1.375,
    'sedang' => 1.55,
    'aktif' => 1.725,
    'sangat aktif' => 1.9
  ];
  return $bmr * ($faktor_aktivitas[$aktivitas] ?? 1.2);
}

function hitungUmur($tanggal_lahir)
{
  $lahir = new DateTime($tanggal_lahir);
  $hari_ini = new DateTime(); // Tanggal sekarang
  $umur = $hari_ini->diff($lahir);
  return $umur->y; // Ambil umur dalam tahun
}

function getCurrentDate()
{
  return date('Y-m-d');
}

function setFlash($type, $message)
{
  $_SESSION['message'] = [
    "type" => $type,
    "message" => $message
  ];
}

function getFlash($key)
{
  if (!isset($_SESSION[$key])) return null;
  $msg = $_SESSION[$key];
  unset($_SESSION[$key]);
  return $msg;
}

function renderView($view, $data = [])
{
  extract($data);

  // echo "<pre>";
  // echo "DUMP di renderView:\n";
  // var_dump($userData, $weeklyFoodData); // <- ini harus muncul
  // echo "</pre>";

  require_once "views/{$view}.php";
}


function getCurrentTime()
{
  return date('H:i:s');
}

function updateSession($db, $id)
{
  $profile = new Profile($db);
  $user = $profile->getUserById($id);

  if ($user) {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['bio'] = $user['bio'];
    $_SESSION['profile_picture'] = $user['profile_picture'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['phone_number'] = $user['phone_number'];
    $_SESSION['jenis_kelamin'] = $user['jenis_kelamin'] == 0 ? 'Perempuan' : 'Laki-laki';
    $_SESSION['tanggal_lahir'] = $user['tanggal_lahir'];
    $_SESSION['umur'] = hitungUmur($user['tanggal_lahir']);
    $_SESSION['is_premium'] = $user['is_premium'] == 1 ? true : false;
    $_SESSION['tinggi_badan'] = $user['tinggi_badan'];
    $_SESSION['berat_badan'] = $user['berat_badan'];
    $_SESSION['aktivitas'] = $user['aktivitas'];

    if ($user['berat_badan'] !== NULL) {
      $_SESSION['bmi'] = hitungBMI($user['berat_badan'], $user['tinggi_badan']);
      $_SESSION['bmi_status'] = $_SESSION['bmi'] < 18.5 ? 'Kurus' : ($_SESSION['bmi'] < 25 ? 'Normal' : 'Gemuk');

      $_SESSION['bmr'] = hitungBMR($user['berat_badan'], $user['tinggi_badan'], $_SESSION['umur'], $user['jenis_kelamin']);

      $_SESSION['tdee'] = hitungTDEE($_SESSION['bmr'], $user['aktivitas']);
    }
    return true;
  } else {
    return false;
  }
}

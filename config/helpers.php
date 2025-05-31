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

function dispatchRoute($route)
{
  try {
    $handler = $route['handler'];
    $middlewares = $route['middleware'] ?? [];
    $params = $route['params'] ?? [];

    // Jalankan middleware
    foreach ($middlewares as $mw) {
      runMiddleware($mw);
    }

    // Resolve closure param
    $params = array_map(function ($p) {
      return is_callable($p) ? $p() : $p;
    }, $params);

    // Eksekusi handler
    if (is_callable($handler)) {
      return call_user_func_array($handler, $params);
    }

    throw new Exception("Invalid handler");
  } catch (Exception $e) {
    http_response_code(500);
    echo "Internal Server Error: " . $e->getMessage();
  }
}

function setFlash($type, $message)
{
  $_SESSION['message'] = [
    "type" => $type,
    "message" => $message
  ];
}

function renderView($view, $data = [])
{

  extract($data);

  // echo "<pre>";
  // echo "DUMP di renderView:\n";
  // var_dump($data); // <- ini harus muncul
  // echo "</pre>";

  require_once "views/{$view}.php";
}


function getCurrentTime()
{
  return date('H:i:s');
}

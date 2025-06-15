<?php
/**
 * Calculates the Body Mass Index (BMI).
 *
 * @param float $berat The weight in kilograms.
 * @param float $tinggi The height in centimeters.
 * @return float The calculated BMI.
 */
function hitungBMI($berat, $tinggi)
{
  $tinggi_m = $tinggi / 100;
  if ($tinggi_m == 0) {
    return 0; // Prevent division by zero if height is 0
  }
  return $berat / ($tinggi_m * $tinggi_m);
}

/**
 * Calculates the Basal Metabolic Rate (BMR).
 *
 * @param float $berat The weight in kilograms.
 * @param float $tinggi The height in centimeters.
 * @param int $umur The age in years.
 * @param int $jenis_kelamin The gender (1 for male, 0 for female).
 * @return float The calculated BMR.
 */
function hitungBMR($berat, $tinggi, $umur, $jenis_kelamin)
{
  if ($jenis_kelamin == 1) {
    return 88.36 + (13.4 * $berat) + (4.8 * $tinggi) - (5.7 * $umur);
  } else {
    return 447.6 + (9.2 * $berat) + (3.1 * $tinggi) - (4.3 * $umur);
  }
}

/**
 * Calculates the Total Daily Energy Expenditure (TDEE).
 *
 * @param float $bmr The Basal Metabolic Rate.
 * @param string $aktivitas The activity level (e.g., 'sangat ringan', 'ringan', 'sedang', 'aktif', 'sangat aktif').
 * @return float The calculated TDEE.
 */
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

/**
 * Calculates the age from a given birth date.
 *
 * @param string $tanggal_lahir The birth date in a format recognized by DateTime (e.g., 'YYYY-MM-DD').
 * @return int The age in years.
 */
function hitungUmur($tanggal_lahir)
{
  if (empty($tanggal_lahir)) {
    return 0; // Return 0 or null if birth date is not provided
  }
  $lahir = new DateTime($tanggal_lahir);
  $hari_ini = new DateTime(); // Tanggal sekarang
  $umur = $hari_ini->diff($lahir);
  return $umur->y; // Ambil umur dalam tahun
}

/**
 * Returns the current date in 'YYYY-MM-DD' format.
 *
 * @return string The current date.
 */
function getCurrentDate()
{
  return date('Y-m-d');
}

/**
 * Dispatches a route by executing its middleware and handler.
 *
 * @param array $route An associative array containing 'handler', 'middleware' (optional), and 'params' (optional).
 * @return mixed The result of the handler execution.
 * @throws Exception If the handler is invalid or an internal server error occurs.
 */
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

/**
 * Sets a flash message in the session.
 *
 * @param string $type The type of the message (e.g., 'success', 'error', 'warning').
 * @param string $message The message content.
 * @return void
 */
function setFlash($type, $message)
{
  $_SESSION['message'] = [
    "type" => $type,
    "message" => $message
  ];
}

/**
 * Renders a view file.
 *
 * @param string $view The name of the view file (without .php extension).
 * @param array $data An associative array of data to extract and make available in the view.
 * @return void
 */
require_once 'config/koneksi.php';
require_once 'models/Profile.php';

function renderView($view, $data = [])
{
  global $db; // Access the global database connection

  // Fetch user data if logged in
  if (isset($_SESSION['user_id'])) {
    $profileModel = new Models\Profile($db);
    $user = $profileModel->getUserById($_SESSION['user_id']);
    if ($user) {
      $data['user'] = $user; // Add user data to the data array
    }
  }

  extract($data);

  // echo "<pre>";
  // echo "DUMP di renderView:\n";
  // var_dump($data); // <- ini harus muncul
  // echo "</pre>";

  require_once "views/{$view}.php";
}

/**
 * Returns the current time in 'HH:MM:SS' format.
 *
 * @return string The current time.
 */
function getCurrentTime()
{
  return date('H:i:s');
}

/**
 * Renders a view within the main application layout.
 *
 * @param string $view The name of the view file (without .php extension).
 * @param array $data An associative array of data to extract and make available in the view.
 * @return void
 */
function renderLayout($view, $data = [])
{
  global $db; // Access the global database connection

  // Fetch user data if logged in
  if (isset($_SESSION['user_id'])) {
    $profileModel = new Models\Profile($db);
    $user = $profileModel->getUserById($_SESSION['user_id']);
    if ($user) {
      $data['user'] = $user; // Add user data to the data array
    }
  }

  extract($data); // Extract data for the view
  ob_start(); // Start output buffering
  require_once "views/{$view}.php"; // Include the actual view file
  $viewContent = ob_get_clean(); // Get the buffered content

  require_once 'views/layout/app.php'; // Include the layout
}

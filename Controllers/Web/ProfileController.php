<?php

namespace Controllers\Web;

use PDO, PDOException, Exception;
use Models\Profile;

require_once 'models/Profile.php';
require_once 'controllers/Web/FoodController.php';

class ProfileController
{
  private $db;
  private $profile;

  public function __construct($db)
  {
    $this->db = $db;
    $this->profile = new Profile($this->db);
  }

  // Helper buat fetch data user
  private function fetchUserData($id)
  {
    $userData = $this->profile->getUserById($id);
    if (!$userData) {
      echo "User not found";
      exit;
    }
    return $userData;
  }

  public function editProfile($id)
  {
    if (!isset($_SESSION['user_id'])) {
      header("Location: /nutritrack/login");
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['profile_picture']['tmp_name'];
        $fileName = basename($_FILES['profile_picture']['name']);
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid('file_') . '.' . $fileExt;

        $uploadDir = 'public/uploads/';
        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $uploadPath)) {
          // path relatif untuk disimpan di DB
          $dbPath = 'public/uploads/' . $newFileName;

          $data = [...$_POST, 'profile_picture' => $dbPath];

          $this->profile->updateProfile($data);
          header('Location: /nutritrack/profile/personal');
          exit;
        } else {
          echo "Error uploading file";
          exit;
        }
      }
    }

    $user = $this->fetchUserData($id);
    renderView('profile/profile_edit', compact('user'));
  }

  public function dashboard()
  {
    if (!isset($_SESSION['user_id'])) {
      header("Location: /nutritrack/login");
      exit;
    }

    $id = $_SESSION['user_id'];
    $weeklyFoodData = $this->profile->getNutritionInWeekData($id);
    $user = $this->fetchUserData($id);
    renderView('profile/profile', compact('user', 'weeklyFoodData'));
  }

  public function profilePersonal($id)
  {
    $user = $this->fetchUserData($id);
    renderView('profile/profile_personal', compact('user'));
  }

  public function profileTracking()
  {
    $user = $this->fetchUserData($_SESSION['user_id']);
    $foodData = $this->profile->getDetailRegistrasiMakanan($_SESSION['user_id']);
    $totalCalories = $this->profile->getTotalCalories($_SESSION['user_id']);

    $bmr = hitungBMR($user['berat_badan'], $user['tinggi_badan'], hitungUmur($user['tanggal_lahir']), $user['jenis_kelamin']);

    $tdee = hitungTDEE($bmr, $user['aktivitas']);

    $data = [
      'total_calories' => $totalCalories,
      'target_calories' => $tdee,
    ];

    renderView('profile/profile_tracking', compact('foodData', 'user', 'data'));
  }

  public function tambahMakanan()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = $_POST;
      $this->profile->tambahDetailRegistrasiMakanan($data);
      header('Location: /nutritrack/profile/tracking');
      exit;
    } else {
      $foodData = new FoodController($this->db);
      $foodData = $foodData->fetchFoodData();
      $user = $this->fetchUserData($_SESSION['user_id']);
      renderView('profile/profile_input_makanan', compact('user', 'foodData'));
    }
  }
}

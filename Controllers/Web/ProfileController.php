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
    $user = $this->fetchUserData($id);
    return ['view' => 'profile/profile_edit', 'data' => compact('user')];
  }

  public function dashboard()
  {
    if (!isset($_SESSION['user_id'])) {
      header("Location: " . BASE_URL . "login");
      exit;
    }

    $id = $_SESSION['user_id'];
    $weeklyFoodData = $this->profile->getNutritionInWeekData($id);
    $user = $this->fetchUserData($id);
    return ['view' => 'profile/profile', 'data' => compact('user', 'weeklyFoodData')];
  }

  public function profilePersonal($id)
  {
    $user = $this->fetchUserData($id);
    return ['view' => 'profile/profile_personal', 'data' => compact('user')];
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

    return ['view' => 'profile/profile_tracking', 'data' => compact('foodData', 'user', 'data')];
  }

  public function tambahMakanan()
  {
    $foodData = new FoodController($this->db);
    $foodData = $foodData->fetchFoodData();
    $user = $this->fetchUserData($_SESSION['user_id']);
    return ['view' => 'profile/profile_input_makanan', 'data' => compact('user', 'foodData')];
  }
}

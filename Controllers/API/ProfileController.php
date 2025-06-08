<?php

namespace Controllers\API;

use PDO, PDOException, Exception;
use Models\Profile;

require_once 'models/Profile.php';
require_once 'controllers/API/FoodController.php';

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
      $this->profile->updateProfile($_POST);
      header('Location: /nutritrack/profile/personal');
      exit;
    }

    $user = $this->fetchUserData($id);
    renderView('profile_edit', compact('user'));
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
      renderView('profile_input_makanan', compact('user', 'foodData'));
    }
  }

  public function userTrackingData()
  {

    $data = json_decode(file_get_contents('php://input'), true);

    $consumedFoodData = $this->profile->getConsumedFoodData($data['user_id'], $data['tanggal']);

    if (!$consumedFoodData) {
      echo json_encode([
        "status" => "error",
        "message" => "Data tidak ditemukan"
      ]);
      exit;
    }

    echo json_encode([
      "status" => "success",
      "data" => $consumedFoodData
    ]);
  }

  public function getUserTrackingData()
  {
    $data = json_decode(file_get_contents('php://input'), true);

    $trackedNutrient = $this->profile->getTrackedNutrient($data['user_id'], $data['tanggal']);

    if (!$trackedNutrient) {
      echo json_encode([
        "status" => "error",
        "message" => "Data tidak ditemukan"
      ]);
      exit;
    }

    echo json_encode([
      "status" => "success",
      "data" => $trackedNutrient
    ]);
  }
}

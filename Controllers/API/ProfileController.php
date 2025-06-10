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

  public function editProfile()
  {
    error_log("editProfile: Method started.");
    $data = json_decode(file_get_contents('php://input'), true);
    error_log("editProfile: Received data: " . print_r($data, true));

    try {
      $updateResult = $this->profile->updateProfile($data);
      error_log("editProfile: updateProfile result: " . print_r($updateResult, true));

      $response = [
        'status' => 'success',
        'message' => ['Profile updated successfully']
      ];
      error_log("editProfile: Sending response: " . json_encode($response));
      echo json_encode($response);
      exit();
    } catch (Exception $e) {
      error_log("editProfile: Error: " . $e->getMessage());
      http_response_code(500);
      echo json_encode([
        'status' => 'error',
        'message' => ['An error occurred: ' . $e->getMessage()]
      ]);
      exit();
    }
  }

  public function tambahMakanan()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $this->profile->tambahDetailRegistrasiMakanan($data);
    echo json_encode([
      'status' => 'success',
      'message' => ['Food added successfully']
    ]);
    exit();
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

  public function getUserGoal()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $data['user_id'];

    $user = $this->profile->getUserById($user_id);

    if (!$user || !isset($user['bmi']) || !isset($user['bmr']) || !isset($user['tdee'])) {
      echo json_encode([
        "status" => "error",
        "message" => "User goal data not found or incomplete"
      ]);
      exit;
    }

    echo json_encode([
      "status" => "success",
      "data" => [
        'bmi' => $user['bmi'],
        'bmr' => $user['bmr'],
        'tdee' => $user['tdee']
      ]
    ]);
    exit;
  }
}

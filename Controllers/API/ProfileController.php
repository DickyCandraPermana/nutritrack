<?php

namespace Controllers\API;

use Models\Profile;
use PDO, PDOException, Exception;

require_once 'models/Profile.php';
require_once 'controllers/API/FoodController.php';

class ProfileController
{
  private $db;
  private $profile;

  /**
   * Constructor for ProfileController.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct($db)
  {
    $this->db = $db;
    $this->profile = new Profile($this->db);
  }

  /**
   * Helper function to fetch user data by ID.
   *
   * @param int $id The ID of the user.
   * @return array The user data.
   */
  private function fetchUserData($id)
  {
    $userData = $this->profile->getUserById($id);
    if (!$userData) {
      echo "User not found";
      exit;
    }
    return $userData;
  }

  /**
   * Handles profile editing.
   * Updates user profile information based on input data.
   *
   * @return void
   */
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

  /**
   * Adds food consumption details for a user.
   *
   * @return void
   */
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

  /**
   * Retrieves a user's food tracking data for a specific date.
   *
   * @return void
   */
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

  /**
   * Retrieves tracked nutrient data for a user on a specific date.
   *
   * @return void
   */
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

  /**
   * Retrieves a user's BMI, BMR, and TDEE goals.
   *
   * @return void
   */
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

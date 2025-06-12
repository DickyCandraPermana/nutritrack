<?php

namespace Controllers\Web;

use Models\Profile;
use PDO, PDOException, Exception;

require_once 'models/Profile.php';
require_once 'controllers/Web/FoodController.php';

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
   * Displays the profile edit page for a specific user.
   *
   * @param int $id The ID of the user to edit.
   * @return array
   */
  public function editProfile($id)
  {
    $user = $this->fetchUserData($id);
    return ['view' => 'profile/profile_edit', 'data' => compact('user')];
  }

  /**
   * Displays the user dashboard.
   * Fetches weekly food data and user information.
   *
   * @return array
   */
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

  /**
   * Displays the personal profile page for a specific user.
   *
   * @param int $id The ID of the user.
   * @return array
   */
  public function profilePersonal($id)
  {
    $user = $this->fetchUserData($id);
    return ['view' => 'profile/profile_personal', 'data' => compact('user')];
  }

  /**
   * Displays the food tracking page for the logged-in user.
   * Calculates total calories, BMR, and TDEE.
   *
   * @return array
   */
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

  /**
   * Displays the page for adding new food consumption.
   *
   * @return array
   */
  public function tambahMakanan()
  {
    $foodData = new FoodController($this->db);
    $foodData = $foodData->fetchFoodData();
    $user = $this->fetchUserData($_SESSION['user_id']);
    return ['view' => 'profile/profile_input_makanan', 'data' => compact('user', 'foodData')];
  }
}

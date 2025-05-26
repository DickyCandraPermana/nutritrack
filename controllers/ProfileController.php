<?php
require_once 'models/Profile.php';
require_once 'controllers/FoodController.php';

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

  public function dashboard()
  {
    if (!isset($_SESSION['user_id'])) {
      header("Location: /nutritrack/login");
      exit;
    }

    $id = $_SESSION['user_id'];
    $weeklyFoodData = $this->profile->getNutritionInWeekData($id);
    $user = $this->fetchUserData($id);
    renderView('profile', compact('user', 'weeklyFoodData'));
  }

  public function profilePersonal($id)
  {
    $user = $this->fetchUserData($id);
    renderView('profile_personal', compact('user'));
  }

  public function profileTracking()
  {
    $foodData = $this->profile->getDetailRegistrasiMakanan($_SESSION['user_id']);

    renderView('profile_tracking', compact('foodData'));
  }

  public function viewData($id)
  {
    $userData = $this->fetchUserData($id);
    renderView('profile_view_data', compact('userData'));
  }

  public function profileInputMakanan()
  {
    $foodData = new FoodController($this->db);
    $foodData = $foodData->fetchFoodData();
    $userData = $this->fetchUserData($_SESSION['user_id']);
    renderView('profile_input_makanan', compact('user', 'foodData'));
  }

  public function tambahMakanan($data)
  {
    $this->profile->tambahMakanan($data);
    unset($_SESSION['foodData']);
    header('Location: /nutritrack/profile/tracking');
    exit;
  }
}

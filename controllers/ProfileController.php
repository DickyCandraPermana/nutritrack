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

  // Helper buat render view

  public function showProfile($id): void
  {
    $userData = $this->fetchUserData($id);
    renderView('profile', compact('userData'));
  }

  public function editProfile($id)
  {
    $userData = $this->fetchUserData($id);
    renderView('profile_edit', compact('userData'));
  }

  public function dashboard($id)
  {
    $weeklyFoodData = $this->profile->getNutritionInWeekData($id);
    $userData = $this->fetchUserData($id);
    renderView('profile', compact('userData', 'weeklyFoodData'));
  }

  public function profilePersonal($id)
  {
    $userData = $this->fetchUserData($id);
    renderView('profile_personal', compact('userData'));
  }

  public function profileTracking($id)
  {
    $foodData = $this->profile->getUserMakananById($id);

    $_SESSION["makanan_user"] = $foodData;
    renderView('profile_tracking', compact('foodData'));
  }

  public function viewData($id)
  {
    $userData = $this->fetchUserData($id);
    renderView('profile_view_data', compact('userData'));
  }

  public function profileInputMakanan($id)
  {
    $foodData = new FoodController($this->db);
    $foodData = $foodData->fetchFoodData();
    $_SESSION["foodData"] = $foodData;
    $userData = $this->fetchUserData($id);
    renderView('profile_input_makanan', compact('userData'));
  }

  public function updateProfile($data)
  {
    $this->profile->updateProfile($data);
    updateSession($this->db, $data['user_id']);
    header('Location: /nutritrack/profile/personal');
    exit;
  }

  public function tambahMakanan($data)
  {
    $this->profile->tambahMakanan($data);
    unset($_SESSION['foodData']);
    header('Location: /nutritrack/profile/tracking');
    exit;
  }

  public function editMakanan($data)
  {
    $this->profile->editMakanan($data);
  }
}

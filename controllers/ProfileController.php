<?php
require_once 'models/Profile.php';

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
  private function renderView($view, $data = [])
  {
    extract($data); // Convert array jadi variable
    require_once "views/{$view}.php";
  }

  public function showProfile($id): void
  {
    $userData = $this->fetchUserData($id);
    $this->renderView('profile', compact('userData'));
  }

  public function editProfile($id)
  {
    $userData = $this->fetchUserData($id);
    $this->renderView('profile_edit', compact('userData'));
  }

  public function dashboard($id)
  {
    $userData = $this->fetchUserData($id);
    $this->renderView('profile_dashboard', compact('userData'));
  }

  public function profilePersonal($id)
  {
    $userData = $this->fetchUserData($id);
    $this->renderView('profile_personal', compact('userData'));
  }

  public function profileTracking($id)
  {
    $foodData = $this->profile->getUserMakananById($id);

    $_SESSION["makanan_user"] = $foodData;
    $this->renderView('profile_tracking', compact('foodData'));
  }

  public function viewData($id)
  {
    $userData = $this->fetchUserData($id);
    $this->renderView('profile_view_data', compact('userData'));
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
  }

  public function editMakanan($data)
  {
    $this->profile->editMakanan($data);
  }
}

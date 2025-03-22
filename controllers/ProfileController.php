<?php
require_once 'models/Profile.php';

class ProfileController
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function showProfile($id): void
  {
    $profile = new Profile($this->db);
    $userData = $profile->getUserById($id);

    if ($userData) {
      require_once 'views/profile.php';
    } else {
      echo "User not found";
    }
  }

  public function editProfile($id)
  {
    $profile = new Profile($this->db);
    $userData = $profile->getUserById($id);

    if ($userData) {
      require_once 'views/profile_edit.php';
    } else {
      echo "User not found";
    }
  }

  public function dashboard($id)
  {
    $profile = new Profile($this->db);
    $userData = $profile->getUserById($id);

    if ($userData) {
      require_once 'views/profile_dashboard.php';
    } else {
      echo "User not found";
    }
  }

  public function profilePersonal($id)
  {
    $profile = new Profile($this->db);
    $userData = $profile->getUserById($id);

    if ($userData) {
      require_once 'views/profile_personal.php';
    } else {
      echo "User not found";
    }
  }

  public function viewData($id)
  {
    $profile = new Profile($this->db);
    $userData = $profile->getUserById($id);

    if ($userData) {
      require_once 'views/profile_view_data.php';
    } else {
      echo "User not found";
    }
  }

  public function updateProfile($data)
  {
    $profile = new Profile($this->db);
    $profile->updateProfile($data);
    updateSession($this->db, $data['user_id']);
    Header('Location: /nutritrack/profile/personal');
  }

  public function tambahMakanan($data) {
    $profile = new Profile($this->db);
    $profile->tambahMakanan($data);
  }

  public function editMakanan($data) {
    $profile = new Profile($this->db);
    $profile->editMakanan($data);
  }
}

<?php

namespace Controllers\Web;

use Controllers\Web;
use Models\Auth;

require_once 'Models/Auth.php';
require_once 'models/Profile.php';

class AuthController
{
  private $db;
  private $auth;

  public function __construct($db)
  {
    $this->db = $db;
    $this->auth = new Auth($this->db);
  }

  public function login()
  {
    if (isset($_SESSION['user_id'])) {
      header("Location: /nutritrack/profile");
      exit();
    }

    return ['view' => 'login'];
  }

  public function logout()
  {
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL); // Use BASE_URL for consistency
    exit();
  }


  public function register()
  {
    if (isset($_SESSION['user_id'])) {
      header("Location: " . BASE_URL . "profile"); // Use BASE_URL for consistency
      exit();
    }

    return ['view' => 'register'];
  }
}

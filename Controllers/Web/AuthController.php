<?php

namespace Controllers\Web;

use Controllers\Web;
use Models\Auth;
use PDO;

require_once 'Models/Auth.php';
require_once 'models/Profile.php';

class AuthController
{
  private $db;
  private $auth;

  /**
   * Constructor for AuthController.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct($db)
  {
    $this->db = $db;
    $this->auth = new Auth($this->db);
  }

  /**
   * Displays the login page.
   * Redirects to profile if user is already logged in.
   *
   * @return array
   */
  public function login()
  {
    if (isset($_SESSION['user_id'])) {
      header("Location: /nutritrack/profile");
      exit();
    }

    return ['view' => 'login'];
  }

  /**
   * Handles user logout.
   * Destroys session and redirects to base URL.
   *
   * @return void
   */
  public function logout()
  {
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL); // Use BASE_URL for consistency
    exit();
  }

  /**
   * Displays the registration page.
   * Redirects to profile if user is already logged in.
   *
   * @return array
   */
  public function register()
  {
    if (isset($_SESSION['user_id'])) {
      header("Location: " . BASE_URL . "profile"); // Use BASE_URL for consistency
      exit();
    }

    return ['view' => 'register'];
  }
}

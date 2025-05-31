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

    renderView('login');
  }

  public function logout()
  {
    session_destroy();
    header("Location: /nutritrack");
    exit();
  }


  public function register()
  {
    if (isset($_SESSION['user_id'])) {
      header("Location: /nutritrack/profile");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = json_decode(file_get_contents('php://input'), true);

      $errors = $this->auth->validateRegister($data);

      if (!empty($errors)) {
        echo json_encode([
          'status' => 'error',
          'message' => $errors
        ]);
        exit();
      }

      $this->auth->register($data);

      echo json_encode([
        'status' => 'success',
        'message' => ['Berhasil register']
      ]);
      exit();
    } else {
      renderView('register');
    }
  }
}

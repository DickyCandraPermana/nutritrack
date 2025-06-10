<?php

namespace Controllers\API;

use Controllers\API;
use Models\Auth;

require_once 'Models/Auth.php';
require_once 'Models/Profile.php';

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
    $data = json_decode(file_get_contents('php://input'), true);
    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');

    header('Content-Type: application/json');

    if (empty($username) || empty($password)) {
      echo json_encode([
        'status' => 'error',
        'message' => ['Username dan password tidak boleh kosong']
      ]);
      exit();
    }

    $user = $this->auth->getLoginInfo($username);

    if (!$user) {
      echo json_encode([
        'status' => 'error',
        'message' => ['Username tidak ditemukan']
      ]);
      exit();
    }

    if (!password_verify($password, $user['password'])) {
      echo json_encode([
        'status' => 'error',
        'message' => ['Password salah']
      ]);
      exit();
    }

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];

    echo json_encode([
      'status' => 'success',
      'message' => ['Berhasil Login'],
      'role' => $user['role']
    ]);
    exit();
  }


  public function register()
  {
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
  }
}

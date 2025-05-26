<?php
require_once 'models/Auth.php';
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

      echo json_encode([
        'status' => 'success',
        'message' => ['Berhasil Login']
      ]);
      exit();
    }

    renderView('login');
  }


  public function register()
  {
    if (isset($_SESSION['user_id'])) {
      header("Location: /nutritrack/profile");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = $_POST;

      $errors = $this->auth->validateRegister($data);

      if (!empty($errors)) {
        setFlash('errors', $errors);
        renderView('register');
        return;
      }

      $this->auth->register($data);
      setFlash('success', 'Registrasi berhasil. Silakan login.');
      header('Location: /nutritrack/login');
      exit();
    } else {
      renderView('register');
    }
  }
}

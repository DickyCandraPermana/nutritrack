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
      $username = htmlspecialchars(trim($_POST['username']));
      $password = trim($_POST['password']);
      $user = $this->auth->getLoginInfo($username);

      if (!$user) {
        setFlash('error', 'User tidak ditemukan.');
        renderView('login');
        return;
      }

      if (!password_verify($password, $user['password'])) {
        setFlash('error', 'Password salah!');
        renderView('login');
        return;
      }

      $_SESSION['user_id'] = $user['user_id'];
      header("Location: /nutritrack/profile");
      exit();
    } else {
      renderView('login');
    }
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

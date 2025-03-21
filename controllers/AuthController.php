<?php
require_once 'models/Auth.php';
require_once 'models/Profile.php';

class AuthController
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function index()
  {
    require_once 'views/login.php';
  }

  public function login($data)
  {
    $auth = new Auth($this->db);
    $user = $auth->login($data);

    $result = updateSession($this->db, $user['user_id']);

    if($result) {
      header('Location: /nutritrack/profile/dashboard');
      exit();
    } else {
      header('Location: /nutritrack/login');
      exit();
    }
  }

  public function register($data)
  {
    $auth = new Auth($this->db);
    $auth->register($data);
    header('Location: /nutritrack/login');
    exit();
  }
    
}

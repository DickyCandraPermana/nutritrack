<?php
require_once 'models/Food.php';

class HomeController
{

  private $db;
  private $profile;

  public function __construct($db)
  {
    $this->db = $db;
    $this->profile = new Profile($this->db);
  }

  public function index()
  {
    if (isset($_SESSION['user_id'])) {
      $user = $this->profile->getUserById($_SESSION['user_id']);
    }
    renderView('home', compact('user'));
  }

  public function search($data, $page = 1)
  {
    $food = new Food($this->db);
    $foods = $food->search($data, 10, $page);
    extract($foods);
    require_once 'views/search.php';
  }
}

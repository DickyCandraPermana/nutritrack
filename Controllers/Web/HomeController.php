<?php

namespace Controllers\Web;

use Models\Profile;
use Models\Food;

require_once 'models/Profile.php';
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
      $user = $this->profile->getUserById($_SESSION['user_id']) ?? [];
      renderView('home', compact('user'));
    }
    renderView('home');
  }

  public function search($data, $page = 1)
  {
    $food = new Food($this->db);
    $foods = $food->search($data, 10, $page);
    renderView('search', compact("foods"));
  }

  public function premiumPage()
  {
    $user = $this->profile->getUserById($_SESSION['user_id']) ?? [];
    renderView('premium_page', compact('user') ?? []);
  }
}

<?php

namespace Controllers\API;

use Models\Profile;
use Models\Food;
use PDO;

require_once 'models/Profile.php';
require_once 'models/Food.php';

class HomeController
{

  private $db;
  private $profile;

  /**
   * Constructor for HomeController.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct($db)
  {
    $this->db = $db;
    $this->profile = new Profile($this->db);
  }

  /**
   * Displays the home page.
   * Fetches user data if a user is logged in.
   *
   * @return void
   */
  public function index()
  {
    if (isset($_SESSION['user_id'])) {
      $user = $this->profile->getUserById($_SESSION['user_id']) ?? [];
    }
    renderView('home', compact('user') ?? []);
  }

  /**
   * Handles food search functionality.
   *
   * @param string $data The search query.
   * @param int $page The current page number for pagination.
   * @return void
   */
  public function search($data, $page = 1)
  {
    $food = new Food($this->db);
    $foods = $food->search($data, 10, $page);
    renderView('search', compact("foods"));
  }
}

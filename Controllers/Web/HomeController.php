<?php

namespace Controllers\Web;

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
   * @return array
   */
  public function index()
  {
    $user = null;
    if (isset($_SESSION['user_id'])) {
      $user = $this->profile->getUserById($_SESSION['user_id']);
    }
    return ['view' => 'home', 'data' => compact('user')];
  }

  /**
   * Handles food search functionality.
   *
   * @param string $data The search query.
   * @param int $page The current page number for pagination.
   * @return array
   */
  public function search($keyword, $page = 1)
  {
    $food = new Food($this->db);
    $perPage = 10; // This should ideally come from a config or be passed as a parameter
    $searchResult = $food->search($keyword, $perPage, $page);

    $paginated_items = $searchResult[0];
    $total_pages = $searchResult[1];
    $current_page = $page;
    $offset = ($page - 1) * $perPage;

    return ['view' => 'search', 'data' => compact("paginated_items", "total_pages", "current_page", "keyword", "offset")];
  }

  /**
   * Displays the premium page.
   * Fetches user data if a user is logged in.
   *
   * @return array
   */
  public function premiumPage()
  {
    $user = null;
    if (isset($_SESSION['user_id'])) {
      $user = $this->profile->getUserById($_SESSION['user_id']);
    }
    return ['view' => 'premium_page', 'data' => compact('user')];
  }
}

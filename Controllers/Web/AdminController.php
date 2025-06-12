<?php

namespace Controllers\Web;

use Models\Profile;
use Models\Food;
use Models\Admin;
use PDO;

require_once 'models/Profile.php';
require_once 'models/Food.php';
require_once 'models/Admin.php';

class AdminController
{

  private $db;
  private $profile;
  private $admin;

  /**
   * Constructor for AdminController.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct($db)
  {
    $this->db = $db;
    $this->profile = new Profile($this->db);
    $this->admin = new Admin($this->db);
  }

  /**
   * Retrieves data for the admin panel.
   *
   * @return array The admin panel data.
   */
  public function getAdminPanelData()
  {
    $data = $this->admin->getAdminPanelData();
    return $data;
  }

  /**
   * Renders an admin page with common data.
   *
   * @param string $view The view to render.
   * @return array An array containing the view name and data.
   */
  private function renderAdminPage($view)
  {
    $user = null;
    if (isset($_SESSION['user_id'])) {
      $user = $this->profile->getUserById($_SESSION['user_id']);
    }
    $data = $this->getAdminPanelData();
    return ['view' => $view, 'data' => compact('user', 'data')];
  }

  /**
   * Displays the admin dashboard.
   *
   * @return array
   */
  public function index()
  {
    return $this->renderAdminPage('admin/admin_dashboard');
  }

  /**
   * Displays the admin users page.
   *
   * @return array
   */
  public function usersPage()
  {
    return $this->renderAdminPage('admin/admin_user');
  }

  /**
   * Displays the admin add user page.
   *
   * @return array
   */
  public function usersAddPage()
  {
    return $this->renderAdminPage('admin/admin_user_tambah');
  }

  /**
   * Displays the admin edit user page.
   *
   * @return array
   */
  public function usersEditPage()
  {
    return $this->renderAdminPage('admin/admin_user_edit');
  }

  /**
   * Displays the admin foods page.
   *
   * @return array
   */
  public function foodsPage()
  {
    return $this->renderAdminPage('admin/admin_food');
  }

  /**
   * Displays the admin add food page.
   *
   * @return array
   */
  public function foodsAddPage()
  {
    return $this->renderAdminPage('admin/admin_food_tambah');
  }

  /**
   * Displays the admin edit food page.
   *
   * @return array
   */
  public function foodsEditPage()
  {
    return $this->renderAdminPage('admin/admin_food_edit');
  }
}

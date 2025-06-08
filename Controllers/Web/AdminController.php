<?php

namespace Controllers\Web;

use Models\Profile;
use Models\Food;
use Models\Admin;

require_once 'models/Profile.php';
require_once 'models/Food.php';
require_once 'models/Admin.php';

class AdminController
{

  private $db;
  private $profile;
  private $admin;

  public function __construct($db)
  {
    $this->db = $db;
    $this->profile = new Profile($this->db);
    $this->admin = new Admin($this->db);
  }

  public function getAdminPanelData()
  {
    $data = $this->admin->getAdminPanelData();
    return $data;
  }

  private function renderAdminPage($view)
  {
    if (isset($_SESSION['user_id'])) {
      $user = $this->profile->getUserById($_SESSION['user_id']) ?? [];
    }
    $data = $this->getAdminPanelData();
    renderView($view, compact('user', 'data') ?? []);
  }

  public function index()
  {
    $this->renderAdminPage('admin/admin_dashboard');
  }

  public function usersPage()
  {
    $this->renderAdminPage('admin/admin_user');
  }

  public function usersAddPage()
  {
    $this->renderAdminPage('admin/admin_user_tambah');
  }

  public function usersEditPage()
  {
    $this->renderAdminPage('admin/admin_user_edit');
  }

  public function foodsPage()
  {
    $this->renderAdminPage('admin/admin_food');
  }

  public function foodsAddPage()
  {
    $this->renderAdminPage('admin/admin_food_tambah');
  }

  public function foodsEditPage()
  {
    $this->renderAdminPage('admin/admin_food_edit');
  }
}

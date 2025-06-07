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

  public function index()
  {
    if (isset($_SESSION['user_id'])) {
      $user = $this->profile->getUserById($_SESSION['user_id']) ?? [];
    }
    $data = $this->getAdminPanelData();
    renderView('admin/admin_dashboard', compact('user', 'data') ?? []);
  }

  public function usersPage()
  {
    renderView('admin/admin_users');
  }

  public function usersAddPage()
  {
    renderView('admin/admin_tambah_user');
  }

  public function usersEditPage()
  {
    renderView('admin/admin_edit_user');
  }

  public function foodsPage()
  {
    renderView('admin/admin_foods');
  }

  public function foodsAddPage()
  {
    renderView('admin/admin_food_tambah');
  }

  public function foodsEditPage()
  {
    renderView('admin/admin_food_edit');
  }
}

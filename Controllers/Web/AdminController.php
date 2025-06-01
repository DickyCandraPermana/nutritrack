<?php

namespace Controllers\Web;

use Models\Profile;
use Models\Food;

require_once 'models/Profile.php';
require_once 'models/Food.php';

class AdminController
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
    }
    renderView('admin/admin_dashboard', compact('user') ?? []);
  }

  public function tambahUser()
  {
    renderView('admin/admin_tambah_user');
  }
}

<?php

namespace Models;

use PDO, PDOException, Exception;

class Admin
{
  public $db;
  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getAdminPanelData()
  {
    $stmt = $this->db->query("SELECT COUNT(*) FROM users");
    $total_users = $stmt->fetchColumn();

    $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE is_premium = 1");
    $total_premium_users = $stmt->fetchColumn();

    $stmt = $this->db->query("SELECT COUNT(*) FROM makanan");
    $total_food = $stmt->fetchColumn();

    return [
      "total_users" => $total_users,
      "total_premium_users" => $total_premium_users,
      "total_food" => $total_food,
      "total_scans" => 0
    ];
  }
}

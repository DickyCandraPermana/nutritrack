<?php

namespace Models;

use PDO, PDOException, Exception;

class Admin
{
  public $db;
  /**
   * Constructor for Admin model.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  /**
   * Retrieves various statistics for the admin panel.
   *
   * @return array An associative array containing total users, total premium users, total food items, and total scans.
   */
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

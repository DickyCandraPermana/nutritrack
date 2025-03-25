<?php

class Food {
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getNamaMakananDanID() {
    $stmt = $this->db->prepare("SELECT food_id, nama_makanan FROM makanan");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
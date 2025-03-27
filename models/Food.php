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

  public function search($search) {
    $stmt = $this->db->prepare("SELECT food_id, nama_makanan FROM makanan WHERE nama_makanan LIKE :search");
    $stmt->bindValue(':search', "%{$search}%", PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
<?php

class Food
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getNamaMakananDanID()
  {
    $stmt = $this->db->prepare("SELECT food_id, nama_makanan FROM makanan");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function search($search, $perPage, $page)
  {
    $total = $this->db->query("SELECT COUNT(*) FROM makanan")->fetchColumn();
    $totalPages = ceil($total / $perPage);

    $offset = ($page - 1) * $perPage;
    var_dump($search);
    $stmt = $this->db->prepare("SELECT food_id, nama_makanan FROM makanan WHERE nama_makanan LIKE ? LIMIT ? OFFSET ?");
    $stmt->bindValue(1, '%' . $search . '%', PDO::PARAM_STR);
    $stmt->bindValue(2, $perPage, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $currentFoods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    var_dump([
      $currentFoods,
      $totalPages
    ]);
    return [
      $currentFoods,
      $totalPages
    ];
  }
}

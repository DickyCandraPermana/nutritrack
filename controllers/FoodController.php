<?php
require_once 'models/Food.php';

class FoodController {
  private $db;
  private $food;
  public function __construct($db)
  {
    $this->db = $db;
    $this->food = new Food($this->db);
  }

  public function fetchFoodData()
  {
    $foodData = $this->food->getNamaMakananDanID();
    if (!$foodData) {
      echo "User not found";
      exit;
    }
    return $foodData;
  }

  public function showFoodPage() {
    $foodData = $this->fetchFoodData();
    require_once 'views/search.php';
  }
}
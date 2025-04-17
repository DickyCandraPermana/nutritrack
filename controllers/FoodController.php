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

  public function renderView($view, $data = []){
    extract($data);
    require_once "views/{$view}.php";
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
    $this->renderView('search', compact('foodData'));
  }
}
<?php
require_once 'models/Food.php';

class HomeController {

  private $db;

  public function __construct($db){
    $this->db = $db;
  }

  public function search($data) {
    $food = new Food($this->db);
    $foods = $food->search($data['search']);
    $_SESSION['foodData'] = $foods;
    require_once 'views/search.php';
  }
}
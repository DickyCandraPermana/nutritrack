<?php
require_once 'models/Food.php';

class HomeController
{

  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function search($data, $page = 1)
  {
    $food = new Food($this->db);
    $foods = $food->search($data, 10, $page);
    var_dump($data);
    extract($foods);
    require_once 'views/search.php';
  }
}

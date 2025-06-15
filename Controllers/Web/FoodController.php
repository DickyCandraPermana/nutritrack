<?php

namespace Controllers\Web;

use PDO, PDOException, Exception;
use Models\Food;

require_once 'models/Food.php';

class FoodController
{
  private PDO $db;
  private Food $food;

  /**
   * Constructor for FoodController.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct(PDO $db)
  {
    $this->db = $db;
    $this->food = new Food($this->db);
  }

  /**
   * Fetches all food data.
   *
   * @return array An array of food data.
   */
  public function fetchFoodData(): array
  {
    $foodData = $this->food->getNamaMakananDanID();

    if (empty($foodData)) {
      setFlash('error', 'Tidak ada data makanan ditemukan!');
      exit;
    }

    return $foodData;
  }

  /**
   * Displays the food detail page based on the provided ID.
   *
   * @param int|string $id The ID of the food item.
   * @return array
   */
  public function foodDetail($id): array
  {
    if (!$id || !is_numeric($id)) {
      setFlash('error', 'ID makanan tidak valid.');
      header('Location: ' . BASE_URL . 'search');
      exit;
    }

    $result = $this->food->getFoodDetail($id);

    if ($result && count($result)) {
      return ['view' => 'food_details', 'data' => ['details' => $result]];
    } else {
      setFlash('error', 'Makanan yang dicari tidak ditemukan.');
      header('Location: ' . BASE_URL . 'search');
      exit;
    }
  }
}

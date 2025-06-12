<?php

namespace Controllers\API;

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
      // Jangan asal echo, kirim ke flash dan redirect lebih baik
      setFlash('error', 'Tidak ada data makanan ditemukan!');
      exit;
    }

    return $foodData;
  }

  /**
   * Displays the food detail page based on the provided ID.
   *
   * @param int|string $id The ID of the food item.
   * @return void
   */
  public function foodDetail($id): void
  {
    if (!$id || !is_numeric($id)) {
      setFlash('error', 'ID makanan tidak valid.');
      header('Location: /foods');
      exit;
    }

    $result = $this->food->getFoodDetail($id);

    if ($result && count($result)) {
      renderView('food_details', ['details' => $result]);
    } else {
      setFlash('error', 'Makanan yang dicari tidak ditemukan.');
      header('Location: /foods');
      exit;
    }
  }

  /**
   * Displays the food search page.
   *
   * @return void
   */
  public function showFoodPage(): void
  {
    $foodData = $this->fetchFoodData();
    renderView('search', ['foodData' => $foodData]);
  }
}

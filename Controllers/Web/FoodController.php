<?php

namespace Controllers\Web;

use PDO, PDOException, Exception;
use Models\Food;

require_once 'models/Food.php';

class FoodController
{
  private PDO $db;
  private Food $food;

  public function __construct(PDO $db)
  {
    $this->db = $db;
    $this->food = new Food($this->db);
  }

  /**
   * Ambil semua data makanan, throw error kalau kosong.
   *
   * @return array
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
   * Tampilkan halaman detail makanan berdasarkan ID.
   *
   * @param int|string $id
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

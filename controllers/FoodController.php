<?php
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
      header('Location: /error');
      exit;
    }

    return $foodData;
  }

  /**
   * Tampilkan halaman detail makanan berdasarkan ID.
   *
   * @param int|string $id
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
   * Tampilkan halaman pencarian makanan.
   *
   * @return void
   */
  public function showFoodPage(): void
  {
    $foodData = $this->fetchFoodData();
    renderView('search', ['foodData' => $foodData]);
  }
}

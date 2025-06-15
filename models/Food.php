<?php

namespace Models;

use PDO, PDOException, Exception;

class Food
{
  private PDO $db;

  /**
   * Constructor for Food model.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  /**
   * Fetches all food data.
   * @return array An array of all food items.
   */
  public function getFoods(): array
  {
    $stmt = $this->db->query("SELECT * FROM makanan");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Adds a new food item to the database.
   *
   * @param array $data An associative array containing food details (nama_makanan, deskripsi, kategori).
   * @return bool True on success, false on failure.
   */
  public function tambahMakanan($data)
  {
    try {
      extract($data);
      $stmt = $this->db->prepare("INSERT INTO makanan (nama_makanan, deskripsi, kategori) VALUES (?, ?, ?)");
      $stmt->execute([$nama_makanan, $deskripsi, $kategori]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  /**
   * Edits an existing food item and its nutritional details in the database.
   *
   * @param array $data An associative array containing food_id, nama_makanan, kategori, deskripsi, and nutritions (array of nutrition details).
   * @return bool True on success, false on failure.
   */
  public function editMakanan($data)
  {
    try {
      // Start a transaction
      $this->db->beginTransaction();

      // Update main food details
      $stmt = $this->db->prepare("UPDATE makanan SET nama_makanan = ?, kategori = ?, deskripsi = ? WHERE food_id = ?");
      $foodUpdateSuccess = $stmt->execute([$data['nama_makanan'], $data['kategori'], $data['deskripsi'], $data['food_id']]);

      if (!$foodUpdateSuccess) {
        $this->db->rollBack();
        return false; // Main food update failed
      }

      $nutritionUpdateSuccess = true; // Assume success if no nutritions or all succeed
      if (isset($data['nutritions']) && is_array($data['nutritions'])) {
        foreach ($data['nutritions'] as $nutrition) {
          // Check if nutrition_id, jumlah, and satuan are set and valid
          if (!isset($nutrition['nutrition_id']) || !isset($nutrition['jumlah']) || !isset($nutrition['satuan'])) {
            // Log error or skip this nutrition, but don't fail the whole transaction yet
            error_log("Invalid nutrition data for food_id " . $data['food_id'] . ": " . json_encode($nutrition));
            $nutritionUpdateSuccess = false; // Mark as partial failure
            continue;
          }

          $stmt = $this->db->prepare("SELECT * FROM detail_nutrisi_makanan WHERE food_id = ? AND nutrition_id = ?");
          $stmt->execute([$data['food_id'], $nutrition['nutrition_id']]);
          $cek = $stmt->fetch();

          if ($cek) {
            $stmt = $this->db->prepare("UPDATE detail_nutrisi_makanan SET jumlah = ?, satuan = ? WHERE food_id = ? AND nutrition_id = ?");
            if (!$stmt->execute([$nutrition['jumlah'], $nutrition['satuan'], $data['food_id'], $nutrition['nutrition_id']])) {
              $nutritionUpdateSuccess = false; // Mark as partial failure
            }
          } else {
            $stmt = $this->db->prepare("INSERT INTO detail_nutrisi_makanan (food_id, nutrition_id, jumlah, satuan) VALUES (?, ?, ?, ?)");
            if (!$stmt->execute([$data['food_id'], $nutrition['nutrition_id'], $nutrition['jumlah'], $nutrition['satuan']])) {
              $nutritionUpdateSuccess = false; // Mark as partial failure
            }
          }
        }
      }

      $this->db->commit();
      return $foodUpdateSuccess && $nutritionUpdateSuccess; // Return true if both main food and all nutritions succeeded
    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log("Error editing food: " . $e->getMessage()); // Log the actual error
      return false;
    }
  }

  /**
   * Deletes a food item from the database.
   *
   * @param array $data An associative array containing the food_id to delete.
   * @return bool True on success, false on failure.
   */
  public function deleteMakanan($data)
  {
    try {
      $stmt = $this->db->prepare("DELETE FROM makanan WHERE food_id = ?");
      $stmt->execute([$data['food_id']]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  /**
   * Fetches all food names and their corresponding IDs.
   * @return array An array of associative arrays, each containing 'food_id' and 'nama_makanan'.
   */
  public function getNamaMakananDanID(): array
  {
    $stmt = $this->db->prepare("SELECT food_id, nama_makanan FROM makanan");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Searches for food items based on a keyword, with pagination.
   *
   * @param string $search The search keyword.
   * @param int $perPage The number of items per page.
   * @param int $page The current page number.
   * @return array An array containing two elements: an array of food items and the total number of pages.
   */
  public function search(string $search, int $perPage, int $page): array
  {
    // Hitung total hasil pencarian
    $countStmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM makanan 
            WHERE nama_makanan LIKE :search OR deskripsi LIKE :search
        ");
    $countStmt->execute(['search' => '%' . $search . '%']);
    $totalItems = (int) $countStmt->fetchColumn();
    $totalPages = (int) ceil($totalItems / $perPage);

    // Ambil data dengan limit dan offset
    $offset = ($page - 1) * $perPage;
    $dataStmt = $this->db->prepare("
            SELECT food_id AS id, nama_makanan AS nama, deskripsi
            FROM makanan
            WHERE nama_makanan LIKE :search OR deskripsi LIKE :search
            ORDER BY food_id DESC
            LIMIT :limit OFFSET :offset
            
        ");
    $dataStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    $dataStmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $dataStmt->execute();
    $foods = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

    return [$foods, $totalPages];
  }

  /**
   * Inserts detailed nutrition information for a food item.
   *
   * @param array $nutrisis An array of nutrition details to insert.
   * @param int $food_id The ID of the food item.
   * @return bool True on success, false on failure.
   */
  public function inputDetailMakanan($nutrisis, $food_id)
  {
    try {
      foreach ($nutrisis as $nutrition) {
        $stmt = $this->db->prepare("INSERT INTO detail_nutrisi_makanan (food_id, nutrition_id, jumlah, satuan) VALUES (?, ?, ?, ?)");
        $stmt->execute([$food_id, $nutrition['nutrition_id'], $nutrition['jumlah'], $nutrition['satuan']]);
      }
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  /**
   * Retrieves the food ID for a given food name.
   *
   * @param string $nama_makanan The name of the food.
   * @return int|false The food ID on success, or false if not found.
   */
  public function getFoodId($nama_makanan)
  {
    $stmt = $this->db->prepare("SELECT food_id FROM makanan WHERE nama_makanan = ?");
    $stmt->execute([$nama_makanan]);
    return $stmt->fetchColumn();
  }

  /**
   * Fetches detailed information for a specific food item by ID.
   *
   * @param int|string $id The ID of the food item.
   * @return array An array of associative arrays containing food and nutrition details.
   */
  public function getFoodDetail($id): array
  {
    $stmt = $this->db->prepare("
            SELECT 
                makanan.nama_makanan AS makanan, 
                detail_nutrisi_makanan.jumlah, 
                detail_nutrisi_makanan.satuan, 
                nutrisi.nama AS nutrisi,
                nutrisi.nutrition_id
            FROM makanan 
            INNER JOIN detail_nutrisi_makanan ON makanan.food_id = detail_nutrisi_makanan.food_id 
            INNER JOIN nutrisi ON detail_nutrisi_makanan.nutrition_id = nutrisi.nutrition_id
            WHERE makanan.food_id = ?
        ");
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Fetches basic food information by food ID.
   *
   * @param array $data An associative array containing the food_id.
   * @return array An array of associative arrays containing basic food information.
   */
  public function fetchFoodBiasa($data)
  {
    $stmt = $this->db->prepare("
            SELECT 
                *
            FROM makanan
            WHERE makanan.food_id = ?
        ");
    $stmt->execute([$data['food_id']]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

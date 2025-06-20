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
   * @param array $data An associative array containing food details (nama_makanan, deskripsi, kategori, porsi).
   * @return bool True on success, false on failure.
   */
  public function tambahMakanan($data)
  {
    try {
      extract($data);
      $stmt = $this->db->prepare("INSERT INTO makanan (nama_makanan, deskripsi, kategori, porsi) VALUES (?, ?, ?, ?)");
      $stmt->execute([$nama_makanan, $deskripsi, $kategori, $porsi]);
      return true;
    } catch (PDOException $e) {
      return $e->getMessage(); // Return error message
    }
  }

  /**
   * Edits an existing food item and its nutritional details in the database.
   *
   * @param array $data An associative array containing food_id, nama_makanan, kategori, deskripsi, porsi, and nutritions (array of nutrition details).
   * @return bool True on success, false on failure.
   */
  public function editMakanan($data)
  {
    try {
      // Start a transaction
      $this->db->beginTransaction();

      // Update main food details
      $stmt = $this->db->prepare("UPDATE makanan SET nama_makanan = ?, kategori = ?, deskripsi = ?, porsi = ? WHERE food_id = ?");
      $foodUpdateSuccess = $stmt->execute([$data['nama_makanan'], $data['kategori'], $data['deskripsi'], $data['porsi'], $data['food_id']]);

      if (!$foodUpdateSuccess) {
        $this->db->rollBack();
        return false; // Main food update failed
      }

      // Delete existing nutrition details for this food_id
      $deleteStmt = $this->db->prepare("DELETE FROM detail_nutrisi_makanan WHERE food_id = ?");
      if (!$deleteStmt->execute([$data['food_id']])) {
        $this->db->rollBack();
        return false; // Failed to delete old nutrition details
      }

      // Insert new nutrition details
      $nutritionInsertSuccess = true;
      if (isset($data['nutritions']) && is_array($data['nutritions'])) {
        foreach ($data['nutritions'] as $nutrition) {
          if (!isset($nutrition['nutrition_id']) || !isset($nutrition['jumlah'])) {
            $nutritionInsertSuccess = false;
            continue;
          }
          $stmt = $this->db->prepare("INSERT INTO detail_nutrisi_makanan (food_id, nutrition_id, jumlah) VALUES (?, ?, ?)");
          if (!$stmt->execute([$data['food_id'], $nutrition['nutrition_id'], $nutrition['jumlah']])) {
            $nutritionInsertSuccess = false;
          }
        }
      }
      
      $this->db->commit();
      return $foodUpdateSuccess && $nutritionInsertSuccess; // Return true if both main food and all nutritions succeeded
    } catch (PDOException $e) {
      $this->db->rollBack();
      return $e->getMessage(); // Return error message for debugging
    } catch (Exception $e) {
      $this->db->rollBack();
      return $e->getMessage(); // Return error message for debugging
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
   * Fetches all food names, their corresponding IDs, and their primary unit.
   * @return array An array of associative arrays, each containing 'food_id', 'nama_makanan', and 'satuan'.
   */
  public function getNamaMakananDanID(): array
  {
    $stmt = $this->db->prepare("
            SELECT 
                food_id, 
                nama_makanan, 
                porsi AS satuan
            FROM makanan
            ORDER BY nama_makanan
        ");
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
            SELECT food_id AS id, nama_makanan AS nama, deskripsi, porsi
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
    if (empty($nutrisis)) {
      return true; // No nutritions to add, consider it a success
    }

    try {
      foreach ($nutrisis as $nutrition) {
        // Basic validation for each nutrition item
        if (!isset($nutrition['nutrition_id']) || !isset($nutrition['jumlah']) || !is_numeric($nutrition['jumlah'])) {
          // Log or handle invalid nutrition data if necessary
          continue; // Skip this invalid nutrition entry
        }
        $stmt = $this->db->prepare("INSERT INTO detail_nutrisi_makanan (food_id, nutrition_id, jumlah) VALUES (?, ?, ?)");
        $stmt->execute([$food_id, $nutrition['nutrition_id'], $nutrition['jumlah']]);
      }
      return true;
    } catch (PDOException $e) {
      return $e->getMessage(); // Return error message
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
                makanan.porsi,
                detail_nutrisi_makanan.jumlah, 
                nutrisi.satuan, 
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
                food_id, nama_makanan, deskripsi, kategori, porsi
            FROM makanan
            WHERE makanan.food_id = ?
        ");
    $stmt->execute([$data['food_id']]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

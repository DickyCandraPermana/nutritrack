<?php

namespace Models;

use PDO, PDOException, Exception;

class Food
{
  private PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  /**
   * Ambil semua data makanan.
   * @return array
   */
  public function getFoods(): array
  {
    $stmt = $this->db->query("SELECT * FROM makanan");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

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

  public function editMakanan($data)
  {
    try {
      $stmt = $this->db->prepare("UPDATE makanan SET nama_makanan = ? WHERE food_id = ?");
      $stmt->execute([$data['food_id'], $data['nama_makanan'], $data['id']]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

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
   * Ambil semua nama makanan dan ID-nya.
   * @return array
   */
  public function getNamaMakananDanID(): array
  {
    $stmt = $this->db->prepare("SELECT food_id, nama_makanan FROM makanan");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Cari makanan berdasarkan keyword, paginated.
   *
   * @param string $search
   * @param int $perPage
   * @param int $page
   * @return array [array $foods, int $totalPages]
   */
  public function search(string $search, int $perPage, int $page): array
  {
    // Hitung total hasil pencarian
    $countStmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM makanan 
            WHERE nama_makanan LIKE :search
        ");
    $countStmt->execute(['search' => '%' . $search . '%']);
    $totalItems = (int) $countStmt->fetchColumn();
    $totalPages = (int) ceil($totalItems / $perPage);

    // Ambil data dengan limit dan offset
    $offset = ($page - 1) * $perPage;
    $dataStmt = $this->db->prepare("
            SELECT food_id, nama_makanan 
            FROM makanan 
            WHERE nama_makanan LIKE :search 
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

  public function getFoodId($nama_makanan)
  {
    $stmt = $this->db->prepare("SELECT food_id FROM makanan WHERE nama_makanan = ?");
    $stmt->execute([$nama_makanan]);
    return $stmt->fetchColumn();
  }

  /**
   * Ambil detail makanan berdasarkan ID.
   *
   * @param int|string $id
   * @return array
   */
  public function getFoodDetail($id): array
  {
    $stmt = $this->db->prepare("
            SELECT 
                makanan.nama_makanan AS makanan, 
                detail_nutrisi_makanan.jumlah, 
                detail_nutrisi_makanan.satuan, 
                nutrisi.nama AS nutrisi
            FROM makanan 
            INNER JOIN detail_nutrisi_makanan ON makanan.food_id = detail_nutrisi_makanan.food_id 
            INNER JOIN nutrisi ON detail_nutrisi_makanan.nutrition_id = nutrisi.nutrition_id
            WHERE makanan.food_id = ?
        ");
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

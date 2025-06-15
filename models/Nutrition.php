<?php

namespace Models;

use PDO, PDOException;

class Nutrition
{
  private PDO $db;

  /**
   * Constructor for Nutrition model.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  /**
   * Retrieves all nutrition data from the database.
   *
   * @return array An array of associative arrays, each representing a nutrition item.
   */
  public function getNutritions(): array
  {
    try {
      $stmt = $this->db->query("SELECT * FROM nutrisi ORDER BY nutrition_id DESC");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  /**
   * Retrieves nutrition data by its ID.
   *
   * @param int $id The ID of the nutrition item.
   * @return array|null An associative array representing the nutrition item, or null if not found.
   */
  public function getNutrisiById(int $id): ?array
  {
    try {
      $stmt = $this->db->prepare("SELECT * FROM nutrisi WHERE id = ?");
      $stmt->execute([$id]);
      return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
      return null;
    }
  }

  /**
   * Adds a new nutrition item to the database.
   *
   * @param array $data An associative array containing the 'nama' (name) of the nutrition.
   * @return bool True on success, false if the nutrition already exists or on database error.
   */
  public function tambahNutrisi(array $data): bool
  {
    if ($this->adaNutrisi($data['nama'])) return false;

    try {
      $stmt = $this->db->prepare("INSERT INTO nutrisi (nama) VALUES (?)");
      $stmt->execute([$data['nama']]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  /**
   * Edits an existing nutrition item in the database.
   *
   * @param array $data An associative array containing the 'nama' (name) and 'id' of the nutrition to edit.
   * @return bool True on success, false on database error.
   */
  public function editNutrisi(array $data): bool
  {
    try {
      $stmt = $this->db->prepare("UPDATE nutrisi SET nama = ? WHERE id = ?");
      $stmt->execute([$data['nama'], $data['id']]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  /**
   * Deletes a nutrition item from the database.
   *
   * @param array $data An associative array containing the 'id' of the nutrition to delete.
   * @return bool True on success, false on database error.
   */
  public function deleteNutrisi(array $data): bool
  {
    try {
      $stmt = $this->db->prepare("DELETE FROM nutrisi WHERE id = ?");
      $stmt->execute([$data['id']]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  /**
   * Checks if a nutrition item with the given name already exists in the database.
   *
   * @param string $nama The name of the nutrition to check.
   * @return bool True if the nutrition exists, false otherwise or on database error.
   */
  public function adaNutrisi(string $nama): bool
  {
    try {
      $stmt = $this->db->prepare("SELECT COUNT(*) FROM nutrisi WHERE nama = ?");
      $stmt->execute([$nama]);
      return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
      return false;
    }
  }
}

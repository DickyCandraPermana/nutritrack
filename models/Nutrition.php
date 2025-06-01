<?php

namespace Models;

use PDO, PDOException;

class Nutrition
{
  private PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getNutritions(): array
  {
    try {
      $stmt = $this->db->query("SELECT * FROM nutrisi ORDER BY nutrition_id DESC");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

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

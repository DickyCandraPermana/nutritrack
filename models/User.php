<?php

namespace Models;

use PDO, PDOException, Exception;

class User
{
  private $db;
  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getUsers()
  {
    try {
      $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  public function tambahUser($data)
  {
    if ($this->adaUsername($data['username'])) return false;
    try {
      $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
      $stmt->execute([$data['username'], $data['password']]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function editUser($data)
  {
    try {
      $stmt = $this->db->prepare("UPDATE users SET username = ?, password = ? WHERE user_id = ?");
      $stmt->execute([$data['username'], $data['password'], $data['id']]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function deleteUser($data)
  {
    try {
      $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
      $stmt->execute([$data['id']]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function adaUsername($username)
  {
    try {
      $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
      $stmt->execute([$username]);
      return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
      return false;
    }
  }
}

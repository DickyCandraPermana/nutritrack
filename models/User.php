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
      $stmt = $this->db->query("SELECT * FROM users WHERE status = 1 ORDER BY user_id ASC");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [
        "error" => "jir",
      ];
    }
  }

  public function getUserById($id)
  {
    try {
      $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
      $stmt->execute([$id['user_id']]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [
        "error" => "jir",
      ];
    }
  }

  public function tambahUser($data)
  {
    if ($this->adaUsername($data['username'])) return false;

    try {
      $stmt = $this->db->prepare("INSERT INTO users (username, bio, profile_picture, password, email, jenis_kelamin, tanggal_lahir, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([
        $data['username'],
        $data['bio'],
        $data['profile_picture'],
        password_hash($data['password'], PASSWORD_DEFAULT),
        $data['email'],
        $data['jenis_kelamin'],
        $data['tanggal_lahir'],
        $data['phone_number']
      ]);
      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function editUser($data)
  {
    try {
      // Build the base SQL and parameters
      $sql = "UPDATE users SET username = ?, bio = ?, profile_picture = ?, email = ?, jenis_kelamin = ?, tanggal_lahir = ?, phone_number = ?";
      $params = [
        $data['username'],
        $data['bio'],
        $data['profile_picture'],
        $data['email'],
        $data['jenis_kelamin'],
        $data['tanggal_lahir'],
        $data['phone_number']
      ];

      // If password is provided and not empty, hash it and update
      if (!empty($data['password'])) {
        $sql .= ", password = ?";
        $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
      }

      $sql .= " WHERE user_id = ?";
      $params[] = $data['user_id'];

      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);

      return true;
    } catch (PDOException $e) {
      return false;
    }
  }


  public function deleteUser($data)
  {
    try {
      $stmt = $this->db->prepare("UPDATE users SET status = 0 WHERE user_id = ?");
      $stmt->execute([$data['user_id']]);
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

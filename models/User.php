<?php

namespace Models;

use PDO, PDOException, Exception;

class User
{
  private $db;
  /**
   * Constructor for User model.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct($db)
  {
    $this->db = $db;
  }

  /**
   * Retrieves all active users from the database.
   *
   * @return array An array of associative arrays, each representing a user.
   */
  /**
   * Retrieves all active users from the database with search and pagination.
   *
   * @param string $search The search term for filtering users.
   * @param int $perPage The number of users per page.
   * @param int $page The current page number.
   * @return array An array of associative arrays, each representing a user.
   */
  public function searchUsers($search = "", $perPage = 50, $page = 1)
  {
    try {
      $offset = ($page - 1) * $perPage;
      $sql = "SELECT * FROM users WHERE status = 1";

      if (!empty($search)) {
        $sql .= " AND (username LIKE :search_term_username OR email LIKE :search_term_email)";
      }

      $sql .= " ORDER BY user_id ASC LIMIT :perPage OFFSET :offset";
      $stmt = $this->db->prepare($sql);

      if (!empty($search)) {
        $stmt->bindValue(':search_term_username', "%" . $search . "%", PDO::PARAM_STR);
        $stmt->bindValue(':search_term_email', "%" . $search . "%", PDO::PARAM_STR);
      }

      $stmt->bindValue(':perPage', (int)$perPage, PDO::PARAM_INT);
      $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [
        "error" => $e->getMessage(),
      ];
    }
  }

  /**
   * Retrieves all active users from the database.
   *
   * @return array An array of associative arrays, each representing a user.
   */
  public function getUsers()
  {
    try {
      $stmt = $this->db->query("SELECT * FROM users WHERE status = 1 ORDER BY user_id ASC");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [
        "error" => "Database error: " . $e->getMessage(),
      ];
    }
  }

  /**
   * Retrieves a user by their ID.
   *
   * @param array $id An associative array containing the 'user_id'.
   * @return array|false An associative array representing the user, or false if not found.
   */
  public function getUserById($id)
  {
    try {
      $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
      $stmt->execute([$id['user_id']]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [
        "error" => "Database error: " . $e->getMessage(),
      ];
    }
  }

  /**
   * Adds a new user to the database.
   *
   * @param array $data An associative array containing user details for registration.
   * @return bool True on success, false if username already exists or on database error.
   */
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

  /**
   * Edits an existing user's information in the database.
   *
   * @param array $data An associative array containing user details to update, including 'user_id'.
   * @return bool True on success, false on database error.
   */
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

  /**
   * Deletes a user by setting their status to 0 (inactive).
   *
   * @param array $data An associative array containing the 'user_id' to delete.
   * @return bool True on success, false on database error.
   */
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

  /**
   * Checks if a username already exists in the database.
   *
   * @param string $username The username to check.
   * @return bool True if the username exists, false otherwise or on database error.
   */
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

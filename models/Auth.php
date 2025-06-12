<?php

namespace Models;

use PDO, PDOException, Exception;

require_once 'config/helpers.php';

class Auth
{
  private $db;

  /**
   * Constructor for Auth model.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct($db)
  {
    $this->db = $db;
  }

  /**
   * Validates registration data.
   *
   * @param array $data The registration data.
   * @return array An array of validation errors, if any.
   */
  public function validateRegister($data)
  {
    $errors = [];

    if (empty($data['username'])) {
      $errors[] = 'Username wajib diisi.';
    } else {
      $stmt = $this->db->prepare("SELECT username FROM users WHERE username = ?");
      $stmt->execute([$data['username']]);
      if ($stmt->fetch()) {
        $errors[] = 'Username sudah digunakan.';
      }
    }

    if (empty($data['email'])) {
      $errors[] = 'Email wajib diisi.';
    } else {
      $stmt = $this->db->prepare("SELECT user_id FROM users WHERE email = ?");
      $stmt->execute([$data['email']]);
      if ($stmt->fetch()) {
        $errors[] = 'Email sudah digunakan.';
      }
    }

    if (empty($data['password']) || strlen($data['password']) < 6) {
      $errors[] = 'Password minimal 6 karakter.';
    }

    return $errors;
  }

  /**
   * Registers a new user in the database.
   *
   * @param array $data The user registration data.
   * @return void
   * @throws Exception If registration fails.
   */
  public function register($data)
  {
    $username = trim($data['username']);
    $password = password_hash(trim($data['password']), PASSWORD_DEFAULT);
    $email = trim($data['email']);
    $first_name = trim($data['first_name']);
    $last_name = trim($data['last_name']);
    $jenis_kelamin = $data['jenis_kelamin'];
    $tanggal_lahir = $data['tanggal_lahir'];

    try {
      $stmt = $this->db->prepare("INSERT INTO users (username, password, email, first_name, last_name, jenis_kelamin, tanggal_lahir) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([$username, $password, $email, $first_name, $last_name, $jenis_kelamin, $tanggal_lahir]);
    } catch (PDOException $e) {
      throw new Exception("Gagal register: " . $e->getMessage());
    }
  }

  /**
   * Retrieves login information for a given username or email.
   *
   * @param string $username The username or email to search for.
   * @return array|false An associative array of user login information, or false if not found.
   */
  public function getLoginInfo($username)
  {
    $stmt = $this->db->prepare("SELECT user_id, email, username, password, role FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
  }
}

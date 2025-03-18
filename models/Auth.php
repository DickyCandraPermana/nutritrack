<?php

require_once 'config/helpers.php';

class Auth
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function register($data) {
    $username = $data['username'];
    $password = $data['password'];
    $email = $data['email'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $jenis_kelamin = $data['jenis_kelamin'];
    $tanggal_lahir = $data['tanggal_lahir'];

    $stmt = $this->db->prepare("INSERT INTO users (username, password, email, first_name, last_name, jenis_kelamin, tanggal_lahir) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $email, $first_name, $last_name, $jenis_kelamin, $tanggal_lahir]);
  }

  public function login($data)
  {
    $username = $data['username'];
    $password = $data['password'];

    // Ambil user berdasarkan username dan password (masih plaintext)
    $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? AND password = ?"); 
    $stmt->execute([$username, $password]);

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(PDO::FETCH_ASSOC); // Login berhasil
    } else {
      return false; // Login gagal
    }
  }

}

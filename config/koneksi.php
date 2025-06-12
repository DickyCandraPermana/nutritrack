<?php
/**
 * Database connection configuration and establishment.
 *
 * This file defines the database connection parameters and establishes a PDO connection.
 */

$host = "localhost"; // Ganti sesuai kebutuhan
$dbname = "nutritrack"; // Nama database
$username = "root"; // Username MySQL (default: root)
$password = ""; // Password MySQL (kosongin kalau pakai XAMPP)

try {
  // Bikin koneksi pakai PDO
  $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Aktifin error mode
} catch (PDOException $e) {
  die("Koneksi gagal: " . $e->getMessage()); // Munculin error kalau koneksi gagal
}

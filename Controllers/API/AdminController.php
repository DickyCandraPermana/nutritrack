<?php

namespace Controllers\API;

use Models\Food;
use Models\User;
use Models\Nutrition;

require_once 'models/Food.php';
require_once 'models/User.php';
require_once 'models/Nutrition.php';

class AdminController
{
  private $db;
  private $food;
  private $user;
  private $nutrition;

  public function __construct($db)
  {
    $this->db = $db;
    $this->food = new Food($this->db);
    $this->user = new User($this->db);
    $this->nutrition = new Nutrition($this->db);
  }

  private function respond($success, $messages = [], $data = null)
  {
    header('Content-Type: application/json');
    echo json_encode([
      'status' => $success ? 'success' : 'error',
      'message' => is_array($messages) ? $messages : [$messages],
      'data' => $data
    ]);
    exit();
  }

  private function getInputData()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
      $this->respond(false, 'Invalid input data' . json_encode($data));
      exit;
    }
    return $data;
  }

  public function getUsers()
  {
    $users = $this->user->getUsers();
    if ($users == null || $users == []) {
      $this->respond(false, 'User list is empty');
    }

    $this->respond(true, 'User list retrieved', $users);
  }

  public function getUserById()
  {
    $data = $this->getInputData();
    $user = $this->user->getUserById($data);
    $this->respond(true, 'User list retrieved', $user);
  }

  public function getFoods()
  {
    $data = $this->getInputData();
    $foods = $this->food->search($data['search'] ?? "",  $data['perPage'] ?? 50, $data['page'] ?? 1);
    $this->respond(true, 'Food list retrieved', $foods);
  }

  public function getNutritions()
  {
    $nutritions = $this->nutrition->getNutritions();
    $this->respond(true, 'Nutrition list retrieved', $nutritions);
  }

  public function tambahUser()
  {
    $data = $this->getInputData();
    $success = $this->user->tambahUser($data);
    $this->respond($success, $success ? 'Berhasil tambah user' : 'Gagal tambah user');
  }

  public function editUser()
  {
    $data = $this->getInputData();
    $success = $this->user->editUser($data);
    $this->respond($success, $success ? 'Berhasil edit user' : 'Gagal edit user');
  }

  public function deleteUser()
  {
    $data = $this->getInputData();
    $success = $this->user->deleteUser($data);
    $this->respond($success, $success ? 'Berhasil delete user' : 'Gagal delete user');
  }

  public function tambahMakanan()
  {
    $data = $this->getInputData();
    extract($data);
    $data = array('nama_makanan' => $nama_makanan, 'deskripsi' => $deskripsi, 'kategori' => $kategori);
    $success = $this->food->tambahMakanan($data);
    if ($success) {
      $food_id = $this->food->getFoodId($data['nama_makanan']);
      $inputDetail = $this->food->inputDetailMakanan($nutrisis, $food_id);
    }

    $this->respond($success && $inputDetail, $success ? 'Berhasil tambah makanan' : 'Gagal tambah makanan');
  }

  public function editMakanan()
  {
    $data = $this->getInputData();
    $success = $this->food->editMakanan($data);
    $this->respond($success, $success ? 'Berhasil edit makanan' : 'Gagal edit makanan');
  }

  public function deleteFood()
  {
    $data = $this->getInputData();
    $success = $this->food->deleteMakanan($data);
    $this->respond($success, $success ? 'Berhasil delete makanan' : 'Gagal delete makanan');
  }

  public function tambahNutrisi()
  {
    $data = $this->getInputData();
    $success = $this->nutrition->tambahNutrisi($data);
    $this->respond($success, $success ? 'Berhasil tambah nutrisi' : 'Gagal tambah nutrisi');
  }

  public function editNutrisi()
  {
    $data = $this->getInputData();
    $success = $this->nutrition->editNutrisi($data);
    $this->respond($success, $success ? 'Berhasil edit nutrisi' : 'Gagal edit nutrisi');
  }

  public function deleteNutrisi()
  {
    $data = $this->getInputData();
    $success = $this->nutrition->deleteNutrisi($data);
    $this->respond($success, $success ? 'Berhasil delete nutrisi' : 'Gagal delete nutrisi');
  }
}

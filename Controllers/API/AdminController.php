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
  }

  private function getInputData()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !is_array($data)) {
      $this->respond(false, 'Invalid input data');
      exit;
    }
    return $data;
  }

  public function getUsers()
  {
    $users = $this->user->getUsers();
    $this->respond(true, 'User list retrieved', $users);
  }

  public function getFoods()
  {
    $foods = $this->food->getFoods();
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
    $success = $this->food->tambahMakanan($data);
    $this->respond($success, $success ? 'Berhasil tambah makanan' : 'Gagal tambah makanan');
  }

  public function editMakanan()
  {
    $data = $this->getInputData();
    $success = $this->food->editMakanan($data);
    $this->respond($success, $success ? 'Berhasil edit makanan' : 'Gagal edit makanan');
  }

  public function deleteMakanan()
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

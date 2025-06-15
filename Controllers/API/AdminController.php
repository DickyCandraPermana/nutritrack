<?php

namespace Controllers\API;

use Models\Food;
use Models\User;
use Models\Nutrition;
use PDO;

require_once 'models/Food.php';
require_once 'models/User.php';
require_once 'models/Nutrition.php';

class AdminController
{
  private $db;
  private $food;
  private $user;
  private $nutrition;

  /**
   * Constructor for AdminController.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct($db)
  {
    $this->db = $db;
    $this->food = new Food($this->db);
    $this->user = new User($this->db);
    $this->nutrition = new Nutrition($this->db);
  }

  /**
   * Responds to API requests with a JSON formatted output.
   *
   * @param bool $success Indicates if the operation was successful.
   * @param string|array $messages A message or array of messages to be included in the response.
   * @param mixed $data Optional data to be returned in the response.
   * @return void
   */
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

  /**
   * Retrieves input data from the request body.
   *
   * @return array The decoded JSON input data.
   */
  private function getInputData()
  {
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        $this->respond(false, 'Invalid JSON input: ' . json_last_error_msg());
    }
    if (!is_array($data)) {
        // If data is not an array, it might be empty or not valid JSON for our purpose
        $data = []; // Treat as empty array to allow default values to kick in
    }
    return $data;
  }

  /**
   * Fetches all users from the database and responds with the list.
   *
   * @return void
   */
  public function getUsers()
  {
    $data = $this->getInputData();
    $search = $data['search'] ?? "";
    $perPage = $data['perPage'] ?? 50;
    $page = $data['page'] ?? 1;

    $users = $this->user->searchUsers($search, $perPage, $page);
    $this->respond(true, 'User list retrieved', $users);
  }

  /**
   * Fetches a user by their ID from the database and responds with the user data.
   *
   * @return void
   */
  public function getUserById()
  {
    $data = $this->getInputData();
    $user = $this->user->getUserById($data);
    $this->respond(true, 'User list retrieved', $user);
  }

  /**
   * Fetches foods based on search criteria and pagination parameters.
   *
   * @return void
   */
  public function getFoods()
  {
    $data = $this->getInputData();
    $foods = $this->food->search($data['search'] ?? "",  $data['perPage'] ?? 50, $data['page'] ?? 1);
    $this->respond(true, 'Food list retrieved', $foods);
  }

  /**
   * Fetches detailed information for a specific food item.
   *
   * @return void
   */
  public function getFoodDetail()
  {
    $data = $this->getInputData();
    $food = $this->food->getFoodDetail($data['food_id']);
    $res = [];
    $temp = [];
    foreach ($food as $nutrition) {
      $temp['nutrition_id'] = $nutrition['nutrition_id'];
      $temp['nama'] = $nutrition['nutrisi'];
      $temp['jumlah'] = $nutrition['jumlah'];
      $temp['satuan'] = $nutrition['satuan'];

      $res[] = $temp;
    }
    $this->respond(true, 'Food detail retrieved', $res);
  }

  /**
   * Fetches basic food information.
   *
   * @return void
   */
  public function fetchFoodBiasa()
  {
    $data = $this->getInputData();
    $food = $this->food->fetchFoodBiasa($data);
    $this->respond(true, 'Food detail retrieved', $food);
  }

  /**
   * Fetches all nutrition types from the database.
   *
   * @return void
   */
  public function getNutritions()
  {
    $nutritions = $this->nutrition->getNutritions();
    $this->respond(true, 'Nutrition list retrieved', $nutritions);
  }

  /**
   * Adds a new user to the database.
   *
   * @return void
   */
  public function tambahUser()
  {
    $data = $_POST; // Get data from POST
    $profilePicturePath = null;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
      $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
      $fileName = $_FILES['profile_picture']['name'];
      $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
      $uploadFileDir = 'public/uploads/';
      $dest_path = $uploadFileDir . $newFileName;

      if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $profilePicturePath = $dest_path;
      } else {
        $this->respond(false, 'Failed to upload profile picture.');
      }
    }

    $data['profile_picture'] = $profilePicturePath; // Add path to data array

    $success = $this->user->tambahUser($data);
    $this->respond($success, $success ? 'Berhasil tambah user' : 'Gagal tambah user');
  }

  /**
   * Edits an existing user in the database.
   *
   * @return void
   */
  public function editUser()
  {
    $data = $_POST; // Get data from POST
    $profilePicturePath = null;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
      $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
      $fileName = $_FILES['profile_picture']['name'];
      $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
      $uploadFileDir = 'public/uploads/';
      $dest_path = $uploadFileDir . $newFileName;

      if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $profilePicturePath = $dest_path;
      } else {
        $this->respond(false, 'Failed to upload profile picture.');
      }
    }

    // Only update profile_picture if a new one was uploaded
    if ($profilePicturePath !== null) {
        $data['profile_picture'] = $profilePicturePath;
    } else {
        // If no new picture is uploaded, ensure the existing one is not overwritten with null
        // This might require fetching the current profile picture from the DB if not already in $data
        // For now, we assume the model handles not updating if the key is missing or null
    }

    $success = $this->user->editUser($data);
    $this->respond($success, $success ? 'Berhasil edit user' : 'Gagal edit user');
  }

  /**
   * Deletes a user from the database.
   *
   * @return void
   */
  public function deleteUser()
  {
    $data = $this->getInputData();
    $success = $this->user->deleteUser($data);
    $this->respond($success, $success ? 'Berhasil delete user' : 'Gagal delete user');
  }

  /**
   * Adds a new food item and its nutritional details to the database.
   *
   * @return void
   */
  public function tambahMakanan()
  {
    $data = $this->getInputData();
    $errors = [];

    $nama_makanan = trim($data['nama_makanan'] ?? '');
    $deskripsi = trim($data['deskripsi'] ?? '');
    $kategori = trim($data['kategori'] ?? '');
    $nutrisis = $data['nutrisis'] ?? [];

    if (empty($nama_makanan)) {
      $errors[] = 'Nama makanan wajib diisi.';
    }

    if (empty($nutrisis)) {
      $errors[] = 'Setidaknya satu nutrisi wajib ditambahkan.';
    } else {
      foreach ($nutrisis as $index => $nutrisi) {
        if (!isset($nutrisi['jumlah']) || !is_numeric($nutrisi['jumlah']) || $nutrisi['jumlah'] <= 0) {
          $errors[] = 'Jumlah nutrisi untuk ' . ($nutrisi['nama'] ?? 'item ' . ($index + 1)) . ' harus angka positif.';
        }
        if (!isset($nutrisi['satuan']) || trim($nutrisi['satuan']) === '') {
          $errors[] = 'Satuan nutrisi untuk ' . ($nutrisi['nama'] ?? 'item ' . ($index + 1)) . ' wajib diisi.';
        }
      }
    }

    if (!empty($errors)) {
      $this->respond(false, $errors);
    }

    $foodData = array('nama_makanan' => $nama_makanan, 'deskripsi' => $deskripsi, 'kategori' => $kategori);
    $success = $this->food->tambahMakanan($foodData);

    $inputDetail = false;
    if ($success) {
      $food_id = $this->food->getFoodId($foodData['nama_makanan']);
      if ($food_id) {
        $inputDetail = $this->food->inputDetailMakanan($nutrisis, $food_id);
      } else {
        $errors[] = 'Gagal mendapatkan ID makanan baru.';
        $this->respond(false, $errors);
      }
    }

    $this->respond($success && $inputDetail, $success && $inputDetail ? 'Berhasil tambah makanan' : 'Gagal tambah makanan');
  }

  /**
   * Edits an existing food item in the database.
   *
   * @return void
   */
  public function editMakanan()
  {
    $data = $this->getInputData();
    $success = $this->food->editMakanan($data);
    $this->respond($success, $success ? 'Berhasil edit makanan' : 'Gagal edit makanan');
  }

  /**
   * Deletes a food item from the database.
   *
   * @return void
   */
  public function deleteFood()
  {
    $data = $this->getInputData();
    $success = $this->food->deleteMakanan($data);
    $this->respond($success, $success ? 'Berhasil delete makanan' : 'Gagal delete makanan');
  }

  /**
   * Adds a new nutrition entry to the database.
   *
   * @return void
   */
  public function tambahNutrisi()
  {
    $data = $this->getInputData();
    $success = $this->nutrition->tambahNutrisi($data);
    $this->respond($success, $success ? 'Berhasil tambah nutrisi' : 'Gagal tambah nutrisi');
  }

  /**
   * Edits an existing nutrition entry in the database.
   *
   * @return void
   */
  public function editNutrisi()
  {
    $data = $this->getInputData();
    $success = $this->nutrition->editNutrisi($data);
    $this->respond($success, $success ? 'Berhasil edit nutrisi' : 'Gagal edit nutrisi');
  }

  /**
   * Deletes a nutrition entry from the database.
   *
   * @return void
   */
  public function deleteNutrisi()
  {
    $data = $this->getInputData();
    $success = $this->nutrition->deleteNutrisi($data);
    $this->respond($success, $success ? 'Berhasil delete nutrisi' : 'Gagal delete nutrisi');
  }
}

<?php

namespace Controllers\API;

use Models\Profile;
use PDO, PDOException, Exception, Throwable, ErrorException, DateTime;

require_once 'models/Profile.php';

// Custom error handler to convert errors into exceptions
set_error_handler(function ($severity, $message, $file, $line) {
  if (!(error_reporting() & $severity)) {
    // This error code is not included in error_reporting
    return false;
  }
  throw new ErrorException($message, 0, $severity, $file, $line);
});

// Custom exception handler to ensure JSON response for all uncaught exceptions
set_exception_handler(function (Throwable $exception) {
  http_response_code(500);
  echo json_encode([
    'status' => 'error',
    'message' => ['An unexpected server error occurred.'] // Generic message for production
  ]);
  exit();
});

class ProfileController
{
  private $db;
  private $profile;

  /**
   * Constructor for ProfileController.
   *
   * @param PDO $db The database connection object.
   */
  public function __construct($db)
  {
    $this->db = $db;
    $this->profile = new Profile($this->db);
  }

  /**
   * Helper function to fetch user data by ID.
   *
   * @param int $id The ID of the user.
   * @return array The user data.
   */
  private function fetchUserData($id)
  {
    $userData = $this->profile->getUserById($id);
    if (!$userData) {
      echo "User not found";
      exit;
    }
    return $userData;
  }

  /**
   * Handles profile editing.
   * Updates user profile information based on input data.
   *
   * @return void
   */
  public function editProfile()
  {
    $data = $_POST;

    // Check if POST data is empty, which might indicate a problem with FormData submission
    if (empty($data) && empty($_FILES)) {
      http_response_code(400);
      echo json_encode([
        'status' => 'error',
        'message' => ['No data received. Please ensure the form is submitted correctly.']
      ]);
      exit();
    }

    $errors = [];
    $profilePicturePath = null;

    // Validate required fields
    $requiredFields = ['user_id', 'username', 'first_name', 'email', 'tanggal_lahir', 'berat_badan', 'tinggi_badan', 'aktivitas'];
    foreach ($requiredFields as $field) {
      if (!isset($data[$field]) || trim($data[$field]) === '') {
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
      }
    }

    // Validate email format
    if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Invalid email format.';
    }

    // Validate numeric fields and ensure they are positive
    $numericFields = ['berat_badan', 'tinggi_badan'];
    foreach ($numericFields as $field) {
      if (isset($data[$field])) {
        // Use filter_var with FILTER_VALIDATE_FLOAT for robust numeric validation
        $value = filter_var($data[$field], FILTER_VALIDATE_FLOAT);
        if ($value === false || $value <= 0) {
          $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' must be a positive number.';
        } else {
          // Update data with validated float value
          $data[$field] = $value;
        }
      }
    }

    // Validate tanggal_lahir format
    if (isset($data['tanggal_lahir'])) {
      $date = DateTime::createFromFormat('Y-m-d', $data['tanggal_lahir']);
      if (!$date || $date->format('Y-m-d') !== $data['tanggal_lahir']) {
        $errors[] = 'Invalid date format for Tanggal Lahir. Please use YYYY-MM-DD.';
      }
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
      http_response_code(400);
      echo json_encode([
        'status' => 'error',
        'message' => $errors
      ]);
      exit();
    }

    try {
      if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        try {
          $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
          $fileName = $_FILES['profile_picture']['name'];
          $fileSize = $_FILES['profile_picture']['size'];
          $fileType = $_FILES['profile_picture']['type'];
          $fileNameCmps = explode(".", $fileName);
          $fileExtension = strtolower(end($fileNameCmps));

          $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
          $uploadFileDir = 'public/uploads/';
          $dest_path = $uploadFileDir . $newFileName;

          if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
          }

          if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $profilePicturePath = $dest_path;
            $data['profile_picture'] = $profilePicturePath;
          } else {
            $uploadError = $_FILES['profile_picture']['error'];
            $errorMessage = 'Failed to move uploaded file. Error code: ' . $uploadError;
            switch ($uploadError) {
              case UPLOAD_ERR_INI_SIZE:
              case UPLOAD_ERR_FORM_SIZE:
                $errorMessage = 'Uploaded file exceeds maximum file size.';
                break;
              case UPLOAD_ERR_PARTIAL:
                $errorMessage = 'File upload was interrupted.';
                break;
              case UPLOAD_ERR_NO_FILE:
                $errorMessage = 'No file was uploaded.';
                break;
              case UPLOAD_ERR_NO_TMP_DIR:
                $errorMessage = 'Missing a temporary folder for uploads.';
                break;
              case UPLOAD_ERR_CANT_WRITE:
                $errorMessage = 'Failed to write file to disk. Check permissions.';
                break;
              case UPLOAD_ERR_EXTENSION:
                $errorMessage = 'A PHP extension stopped the file upload.';
                break;
            }
            throw new Exception($errorMessage);
          }
        } catch (Exception $e) {
          http_response_code(500);
          echo json_encode([
            'status' => 'error',
            'message' => ['File upload error: ' . $e->getMessage()]
          ]);
          exit();
        }
      } else if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadError = $_FILES['profile_picture']['error'];
        $errorMessage = 'File upload error. Error code: ' . $uploadError;
        switch ($uploadError) {
          case UPLOAD_ERR_INI_SIZE:
          case UPLOAD_ERR_FORM_SIZE:
            $errorMessage = 'Uploaded file exceeds maximum file size.';
            break;
          case UPLOAD_ERR_PARTIAL:
            $errorMessage = 'File upload was interrupted.';
            break;
          case UPLOAD_ERR_NO_TMP_DIR:
            $errorMessage = 'Missing a temporary folder for uploads.';
            break;
          case UPLOAD_ERR_CANT_WRITE:
            $errorMessage = 'Failed to write file to disk. Check permissions.';
            break;
          case UPLOAD_ERR_EXTENSION:
            $errorMessage = 'A PHP extension stopped the file upload.';
            break;
        }
        http_response_code(500);
        echo json_encode([
          'status' => 'error',
          'message' => ['File upload error: ' . $errorMessage]
        ]);
        exit();
      }

      $updateResult = $this->profile->updateProfile($data);

      $response = [
        'status' => 'success',
        'message' => ['Profile updated successfully']
      ];
      echo json_encode($response);
      exit();
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => 'error',
        'message' => ['An error occurred: ' . $e->getMessage()]
      ]);
      exit();
    }
  }

  /**
   * Adds food consumption details for a user.
   *
   * @return void
   */
  public function tambahMakanan()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $errors = [];

    // Validate food_id
    if (!isset($data['food_id']) || !is_numeric($data['food_id']) || $data['food_id'] <= 0) {
      $errors[] = 'Food selection is required.';
    }

    // Validate jumlah_porsi
    if (!isset($data['jumlah_porsi']) || !is_numeric($data['jumlah_porsi']) || $data['jumlah_porsi'] <= 0) {
      $errors[] = 'Portion must be a positive number.';
    }

    // Validate satuan
    if (!isset($data['satuan']) || trim($data['satuan']) === '') {
      $errors[] = 'Portion unit is required.';
    }

    if (!empty($errors)) {
      http_response_code(400);
      echo json_encode([
        'status' => 'error',
        'message' => $errors
      ]);
      exit();
    }

    try {
      $this->profile->tambahDetailRegistrasiMakanan($data);
      echo json_encode([
        'status' => 'success',
        'message' => ['Food added successfully']
      ]);
      exit();
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => 'error',
        'message' => ['An error occurred while adding food: ' . $e->getMessage()]
      ]);
      exit();
    }
  }

  /**
   * Retrieves a user's food tracking data for a specific date.
   *
   * @return void
   */
  public function userTrackingData()
  {

    $data = json_decode(file_get_contents('php://input'), true);

    $consumedFoodData = $this->profile->getConsumedFoodData($data['user_id'], $data['tanggal']);

    if (!$consumedFoodData) {
      echo json_encode([
        "status" => "error",
        "message" => "Data tidak ditemukan"
      ]);
      exit;
    }

    echo json_encode([
      "status" => "success",
      "data" => $consumedFoodData
    ]);
  }

  /**
   * Retrieves tracked nutrient data for a user on a specific date.
   *
   * @return void
   */
  public function getUserTrackingData()
  {
    $data = json_decode(file_get_contents('php://input'), true);

    $trackedNutrient = $this->profile->getTrackedNutrient($data['user_id'], $data['tanggal']);

    if (!$trackedNutrient) {
      echo json_encode([
        "status" => "error",
        "message" => "Data tidak ditemukan"
      ]);
      exit;
    }

    echo json_encode([
      "status" => "success",
      "data" => $trackedNutrient
    ]);
  }

  /**
   * Retrieves a user's BMI, BMR, and TDEE goals.
   *
   * @return void
   */
  public function getUserGoal()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $data['user_id'];

    $user = $this->profile->getUserById($user_id);

    if (!$user || !isset($user['bmi']) || !isset($user['bmr']) || !isset($user['tdee'])) {
      echo json_encode([
        "status" => "error",
        "message" => "User goal data not found or incomplete"
      ]);
      exit;
    }

    echo json_encode([
      "status" => "success",
      "data" => [
        'bmi' => $user['bmi'],
        'bmr' => $user['bmr'],
        'tdee' => $user['tdee']
      ]
    ]);
    exit;
  }

  public function addReminder()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $res = $this->profile->addReminder($data);
    if (isset($res['error'])) {
      echo json_encode([
        "status" => "error",
        "message" => $res['error']
      ]);
      exit;
    }
    echo json_encode([
      "status" => "success",
      "message" => "Reminder added successfully"
    ]);
    exit;
  }

  public function getUserReminder()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $data['user_id'];
    $reminders = $this->profile->getUserReminder($user_id);
    echo json_encode([
      "status" => "success",
      "data" => $reminders
    ]);
    exit;
  }

  public function deleteReminder()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $res = $this->profile->deleteReminder($data['reminder_id']);
    if (isset($res['error'])) {
      echo json_encode([
        "status" => "error",
        "message" => $res['error']
      ]);
      exit;
    }
    echo json_encode([
      "status" => "success",
      "message" => "Reminder deleted successfully"
    ]);
    exit;
  }

  public function completeReminder()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $res = $this->profile->completeReminder($data['reminder_id']);
    if (isset($res['error'])) {
      echo json_encode([
        "status" => "error",
        "message" => $res['error']
      ]);
      exit;
    }
    echo json_encode([
      "status" => "success",
      "message" => "Reminder completed successfully"
    ]);
    exit;
  }
}

# NutriTrack

NutriTrack is a web application designed to help users track their food intake and nutritional information. It provides features for managing food items, viewing nutritional details, and tracking personal dietary habits.

## Features

*   **Food Management:** Add, edit, and delete food items with detailed nutritional information.
*   **Nutrition Tracking:** Track daily food consumption and monitor nutritional intake.
*   **User Profiles:** Manage personal profiles and dietary goals.
*   **Search Functionality:** Easily search for food items.
*   **Admin Panel:** (Assumed based on file structure) Manage users and food data.
*   **API Endpoints:** (Assumed based on file structure) Provide data for various functionalities.

## Technologies Used

*   **Backend:** PHP
*   **Database:** MySQL (or compatible, via PDO)
*   **Frontend:** HTML, CSS (likely Tailwind CSS), JavaScript
*   **Web Server:** Apache/Nginx (e.g., Laragon environment)

## Installation

To set up NutriTrack on your local machine, follow these steps:

1.  **Clone the repository:**
    ```bash
    git clone <repository_url>
    cd nutritrack
    ```
    (Replace `<repository_url>` with the actual repository URL if available)

2.  **Set up your web server:**
    Ensure you have a PHP-compatible web server (like Apache or Nginx) configured. If using Laragon, simply place the `nutritrack` folder in your `www` directory.

3.  **Database Setup:**
    *   Create a MySQL database (e.g., `nutritrack_db`).
    *   Import the database schema. (You will need to create this if it doesn't exist. A `database.sql` file is typically provided for this.)
    *   Update the database connection details in `config/koneksi.php` (or similar configuration file) to match your database credentials.

    ```php
    // Example of config/koneksi.php (adjust as per actual file)
    <?php
    $host = 'localhost';
    $db   = 'nutritrack_db';
    $user = 'your_db_user';
    $pass = 'your_db_password';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    ?>
    ```

4.  **Install PHP Dependencies (if any):**
    If the project uses Composer, run:
    ```bash
    composer install
    ```
    (Based on `package.json` and `package-lock.json`, it might use npm/yarn for frontend dependencies, but PHP dependencies would be Composer.)

5.  **Install Frontend Dependencies (if any):**
    If the project uses npm or yarn for frontend assets (e.g., Tailwind CSS compilation), navigate to the project root and run:
    ```bash
    npm install
    # or
    yarn install
    ```
    Then, compile assets:
    ```bash
    npm run dev
    # or
    npm run build
    ```

## Usage

After successful installation, open your web browser and navigate to the URL where your project is hosted (e.g., `http://localhost/nutritrack`).

*   **Register** a new account or **Login** with existing credentials.
*   Explore the dashboard to track your food and nutrition.
*   Use the admin panel (if applicable) for administrative tasks.

## Project Structure (Key Directories)

*   `config/`: Database connection and general configuration files.
*   `Controllers/`: Contains PHP classes for handling web requests (API and Web).
*   `models/`: Contains PHP classes for database interactions and business logic.
*   `views/`: Contains PHP files for rendering the user interface.
*   `public/`: Publicly accessible assets like CSS, JavaScript, images.
*   `routes/`: Defines application routes.
*   `components/`: Reusable UI components.

## Contributing

Contributions are welcome! Please fork the repository and submit pull requests.

## License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT). (You can change this to your preferred license.)

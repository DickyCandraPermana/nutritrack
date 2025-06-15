<?php
// 1. Konfigurasi
require_once 'config/config.php';
require_once 'config/helpers.php';

// Start session and include error handling at the very top
session_start();
include_once 'components/errorHandling.php';

// 2. Routing berdasar URI
$uri = $_SERVER['REQUEST_URI'];

// Remove BASE_URL from URI for routing purposes
$base_url_path = parse_url(BASE_URL, PHP_URL_PATH);
if (strpos($uri, $base_url_path) === 0) {
    $uri = substr($uri, strlen($base_url_path));
}
$uri = trim($uri, '/');


if (strpos($uri, 'api') === 0) { // Check if it's an API route
  require_once 'routes/api.php';
  exit;
}

// Main route dispatcher
$routeResult = null;

if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === "admin") {
    // Admin routes
    require_once 'routes/admin.php'; // This file should define $adminRoutes
    if (isset($adminRoutes[$uri])) {
        $route = $adminRoutes[$uri];
        $routeResult = dispatchRoute($route);
    }
} else {
    // Web routes
    require_once 'routes/web.php'; // This file should define $webRoutes
    if (isset($webRoutes[$uri])) {
        $route = $webRoutes[$uri];
        $routeResult = dispatchRoute($route);
    }
}

if ($routeResult && is_array($routeResult) && isset($routeResult['view'])) {
    // If a view is returned from the controller, render it within the layout
    renderLayout($routeResult['view'], $routeResult['data'] ?? []);
} else if (!$routeResult) {
    // If no route found or dispatchRoute didn't return a view, load 404 page within the layout
    renderLayout('404'); // Assuming 404.php exists in views/
}

?>

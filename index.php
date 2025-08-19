<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$request_uri = trim($_SERVER['REQUEST_URI'], '/');

// Remove the base directory if the application is not in the root
// For example, if your app is at http://localhost/casinha/, then $base_dir = 'casinha';
$base_dir = ''; // Leave empty if your app is in the root of your web server

if (!empty($base_dir) && strpos($request_uri, $base_dir) === 0) {
    $request_uri = substr($request_uri, strlen($base_dir));
    $request_uri = trim($request_uri, '/');
}

// Default to login page if no specific page is requested
if (empty($request_uri)) {
    $request_uri = 'login.php';
}

echo "Request URI: " . $request_uri . "<br>"; // Debugging line

// Map requested URI to actual file path in src/app
$file_path = __DIR__ . '/src/app/' . $request_uri;

echo "File Path: " . $file_path . "<br>"; // Debugging line

// Basic routing: include the file if it exists, otherwise show 404
if (file_exists($file_path)) {
    require_once $file_path;
} else {
    // Handle 404 Not Found
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    echo "<p>The page you requested could not be found.</p>";
}

?>
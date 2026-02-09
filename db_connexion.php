<?php
// Start output buffering
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);

// Get absolute path to .env file
$envPath = __DIR__ . '/.env';

try {
    if (!file_exists($envPath)) {
        throw new Exception("Environment file not found at: $envPath");
    }

    if (!is_readable($envPath)) {
        throw new Exception("Environment file is not readable at: $envPath");
    }

    $env = parse_ini_file($envPath);
    
    if ($env === false) {
        $error = error_get_last();
        throw new Exception("Failed to parse .env file: " . ($error['message'] ?? 'Unknown error'));
    }

    if (empty($env['DB_HOST']) || empty($env['DB_NAME']) || empty($env['DB_USER']) || empty($env['DB_PASS'])) {
        throw new Exception('Missing required environment variables');
    }

    $db = new PDO(
        "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8",
        $env['DB_USER'],
        $env['DB_PASS'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(Exception $e) {
    // Clean any output that might have been generated
    ob_clean();
    header('Content-Type: application/json');
    die(json_encode(['success' => false, 'message' => $e->getMessage()]));
}
?>

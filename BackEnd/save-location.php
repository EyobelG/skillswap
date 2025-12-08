<?php
// backend/save-location.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!isset($data['address']) || !isset($data['latitude']) || !isset($data['longitude']) || !isset($data['userId'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

// Sanitize inputs
$userId = filter_var($data['userId'], FILTER_SANITIZE_EMAIL);
$address = htmlspecialchars($data['address'], ENT_QUOTES, 'UTF-8');
$latitude = floatval($data['latitude']);
$longitude = floatval($data['longitude']);

// Validate coordinates
if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid coordinates']);
    exit();
}

try {
    // 🔹 OPTION 1: Save to Database (Recommended)
    // Uncomment and configure your database connection
    /*
    $host = 'localhost';
    $dbname = 'skillswap';
    $username = 'your_db_user';
    $password = 'your_db_password';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("
        UPDATE users 
        SET location_address = :address, 
            location_lat = :latitude, 
            location_lng = :longitude,
            location_updated_at = NOW()
        WHERE email = :userId
    ");
    
    $stmt->execute([
        ':address' => $address,
        ':latitude' => $latitude,
        ':longitude' => $longitude,
        ':userId' => $userId
    ]);
    */
    
    // 🔹 OPTION 2: Save to JSON file (For testing/development)
    $dataFile = __DIR__ . '/user_locations.json';
    
    // Load existing data
    $locations = [];
    if (file_exists($dataFile)) {
        $locations = json_decode(file_get_contents($dataFile), true) ?: [];
    }
    
    // Update user's location
    $locations[$userId] = [
        'address' => $address,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Save back to file
    file_put_contents($dataFile, json_encode($locations, JSON_PRETTY_PRINT));
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Location saved successfully',
        'data' => [
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>
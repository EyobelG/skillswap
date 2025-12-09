<?php
// CRITICAL: Suppress ALL error output - must be first lines
error_reporting(0);
ini_set('display_errors', 0);

// Force JSON content type immediately
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Function to send JSON response and exit
function sendJSON($data, $statusCode = 200) {
    // Ensure the correct HTTP status code is set
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

// Load config safely
try {
    if (!file_exists(__DIR__ . '/.env.php')) {
        sendJSON(['error' => 'Config file not found', 'fix' => 'Ensure .env.php exists and contains the GEMINI_API_KEY.'], 500);
    }
    // Note: Use a more secure configuration loading method in production, like environment variables
    $config = require __DIR__ . '/.env.php';
    $API_KEY = $config['GEMINI_API_KEY'] ?? null;
} catch (Exception $e) {
    sendJSON(['error' => 'Config file read error', 'details' => $e->getMessage()], 500);
}

if (!$API_KEY) {
    sendJSON(['error' => 'API key not configured in .env.php'], 500);
}

// Get and validate POST data
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    sendJSON(['error' => 'Invalid JSON input', 'details' => json_last_error_msg()], 400);
}

$message = $input['message'] ?? '';
// Note: History should typically be passed in $input['history']
$history = $input['history'] ?? [];

// Default values for persona variables
$memberName = $input['memberName'] ?? 'Instructor';
$memberAge = $input['memberAge'] ?? '40';
$memberCategories = $input['memberCategories'] ?? 'programming';
$memberDescription = $input['memberDescription'] ?? 'A highly experienced mentor.';

if (empty($message)) {
    sendJSON(['error' => 'Message is required'], 400);
}

$systemPrompt = "You are {$memberName}, a {$memberAge}-year-old {$memberCategories} instructor. {$memberDescription}

Your personality and speaking style should match your profession:
- Be friendly, encouraging, and professional
- Share relevant tips and advice about your area of expertise
- Show enthusiasm for teaching and helping others learn
- Keep responses conversational and not too long (2-4 sentences typically)
- Use your expertise naturally in conversation
- Remember: You are chatting with a potential student who wants to learn from you, and potentially swap skills.

--- Start of Current User Message ---";

$contents = [];

// System context + current user message
$currentUserContent = $systemPrompt . "\n\n" . $message;

$contents[] = [
    'role' => 'user',
    'parts' => [
        ['text' => $currentUserContent]
    ]
];

// Prepare API request
$modelName = 'gemini-2.5-flash';
$apiUrl = "https://generativelanguage.googleapis.com/v1/models/{$modelName}:generateContent?key={$API_KEY}";

$requestData = [
    'contents' => $contents,
    'generationConfig' => [
        'temperature' => 0.9,
        'topK' => 40,
        'topP' => 0.95,
        'maxOutputTokens' => 500
    ]
];

// Make API call
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);


// Handle cURL connection errors
if ($curlError) {
    sendJSON(['error' => 'Connection error to external API', 'details' => $curlError, 'fix' => 'Check firewall or network connection.'], 500);
}

// Decode the response for detailed error inspection
$data = json_decode($response, true);
$jsonError = json_last_error();

// Handle non-200 responses
if ($httpCode !== 200) {
    $errorMsg = 'API request failed';
    
    // Check if the external API returned a 4xx client error
    if ($httpCode >= 400 && $httpCode < 500) {
        $errorMsg = 'External API rejected request (Client Error)';
    } 
    // Check if the external API returned a 5xx server error
    elseif ($httpCode >= 500) {
        $errorMsg = 'External API service unavailable (Server Error)';
    }

    // Use the external API's status code for a more accurate frontend error
    $responseCode = ($httpCode >= 400) ? $httpCode : 500; 

    sendJSON([
        'error' => $errorMsg,
        'httpCode' => $httpCode,
        'details' => ($jsonError === JSON_ERROR_NONE && is_array($data)) ? $data : $response // Include raw or parsed response
    ], $responseCode); // Send the accurate status code back to the frontend
}


// Handle invalid JSON response from a successful 200 call
if ($jsonError !== JSON_ERROR_NONE) {
    sendJSON(['error' => 'Invalid API response JSON', 'details' => json_last_error_msg()], 500);
}

// Extract AI response
$aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

if (empty($aiResponse)) {
    // This often means the prompt was blocked by safety filters
    sendJSON([
        'error' => 'Empty API response (potentially blocked)',
        'data' => $data,
        'feedback' => $data['promptFeedback'] ?? null
    ], 500);
}

// Success!
sendJSON(['response' => $aiResponse]);
?>
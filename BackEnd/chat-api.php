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
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

// Load config safely
try {
    if (!file_exists(__DIR__ . '/.env.php')) {
        sendJSON(['error' => 'Config file not found'], 500);
    }
    $config = require __DIR__ . '/.env.php';
    $API_KEY = $config['GEMINI_API_KEY'] ?? null;
} catch (Exception $e) {
    sendJSON(['error' => 'Config error', 'details' => $e->getMessage()], 500);
}

if (!$API_KEY) {
    sendJSON(['error' => 'API key not configured'], 500);
}

// Get and validate POST data
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    sendJSON(['error' => 'Invalid JSON input'], 400);
}

$message = $input['message'] ?? '';
$memberName = $input['memberName'] ?? 'Instructor';
$memberAge = $input['memberAge'] ?? '40';
$memberCategories = $input['memberCategories'] ?? 'programming';
$memberDescription = $input['memberDescription'] ?? 'A highly experienced mentor.';

if (empty($message)) {
    sendJSON(['error' => 'Message is required'], 400);
}

// Build system prompt
$systemPrompt = "You are {$memberName}, a {$memberAge}-year-old {$memberCategories} instructor. {$memberDescription}

Your personality and speaking style should match your profession:
- Be friendly, encouraging, and professional
- Share relevant tips and advice about your area of expertise
- Show enthusiasm for teaching and helping others learn
- Keep responses conversational and not too long (2-4 sentences typically)
- Use your expertise naturally in conversation

Remember: You are chatting with a potential student who wants to learn from you, and potentially swap skills.";

// Prepare API request
$modelName = 'gemini-2.5-flash'; // Using the latest recommended model
$apiUrl = "https://generativelanguage.googleapis.com/v1/models/{$modelName}:generateContent?key={$API_KEY}";

$requestData = [
    'contents' => [
        [
            'role' => 'system',
            'parts' => [
                ['text' => $systemPrompt]
            ]
        ],
        [
            'role' => 'user',
            'parts' => [
                ['text' => $message]
            ]
        ]
    ],
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

// Handle cURL errors
if ($curlError) {
    sendJSON(['error' => 'Connection error', 'details' => $curlError], 500);
}

// Handle non-200 responses
if ($httpCode !== 200) {
    $errorDetails = json_decode($response, true);
    sendJSON([
        'error' => 'API request failed',
        'httpCode' => $httpCode,
        'details' => $errorDetails ?? $response
    ], 500);
}

// Parse response
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    sendJSON(['error' => 'Invalid API response', 'details' => 'Could not parse JSON'], 500);
}

// Extract AI response
$aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

if (empty($aiResponse)) {
    sendJSON([
        'error' => 'Empty API response',
        'data' => $data,
        'feedback' => $data['promptFeedback'] ?? null
    ], 500);
}

// Success!
sendJSON(['response' => $aiResponse]);
?>
<?php
// chat-api.php

// Load config from same directory
$config = require __DIR__ . '/.env.php';
$API_KEY = $config['GEMINI_API_KEY'];

if (!$API_KEY) {
    http_response_code(500);
    echo json_encode(['error' => 'API key not configured']);
    exit;
}

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get POST data - NOW JUST EXPECTING A MESSAGE
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? ''; 
$memberName = $input['memberName'] ?? 'Instructor';
$memberAge = $input['memberAge'] ?? '40';
$memberCategories = $input['memberCategories'] ?? 'programming';
$memberDescription = $input['memberDescription'] ?? 'A highly experienced mentor who specializes in web development.';

if (empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit;
}

// Build the system prompt
$systemPrompt = "You are {$memberName}, a {$memberAge}-year-old {$memberCategories} instructor. {$memberDescription}

Your personality and speaking style should match your profession:
- Be friendly, encouraging, and professional
- Share relevant tips and advice about your area of expertise
- Show enthusiasm for teaching and helping others learn
- Keep responses conversational and not too long (2-4 sentences typically)
- Use your expertise naturally in conversation

Remember: You are chatting with a potential student who wants to learn from you, 
and potentially swap skills.";

// Define the model
$modelName = 'gemini-1.5-flash';

// Prepare API request to Gemini
$apiUrl = "https://generativelanguage.googleapis.com/v1/models/{$modelName}:generateContent?key={$API_KEY}";

// Simple structure - just the current message
$requestData = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => $message]
            ]
        ]
    ],
    'systemInstruction' => [
        'parts' => [
            ['text' => $systemPrompt]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.9,
        'topK' => 40,
        'topP' => 0.95,
        'maxOutputTokens' => 500
    ]
];

// Make the API call using cURL
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch); 

// Handle errors
if ($curlError) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection error', 'details' => $curlError]);
    exit;
}

if ($httpCode !== 200) {
    $errorDetails = json_decode($response, true) ?? ['raw_response' => $response];
    http_response_code(500);
    echo json_encode(['error' => 'API request failed', 'httpCode' => $httpCode, 'details' => $errorDetails]);
    exit;
}

// Parse response
$data = json_decode($response, true);

// Robust check for the response text
$aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

if (empty($aiResponse)) {
    http_response_code(500);
    $feedback = $data['promptFeedback'] ?? 'N/A';
    echo json_encode(['error' => 'Invalid or empty API response', 'data' => $data, 'feedback' => $feedback]);
    exit;
}

// Return the response
echo json_encode(['response' => $aiResponse]);
?>
<?php
require_once _DIR_ . '/vendor/autoload.php';

use Twilio\Rest\Client;

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['to']) || !isset($input['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$to = $input['to'];
$message = $input['message'];

// Twilio credentials from your Twilio dashboard
$account_sid = 'AC6451aa375229df821acd8cf4ea0b9f37';
$auth_token = '83f0b7af792c3ecef10f3a28401a6f65';
$twilio_number = '15076657534';

try {
    $client = new Client($account_sid, $auth_token);
    $client->messages->create($to, [
        'from' => $twilio_number,
        'body' => $message
    ]);

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
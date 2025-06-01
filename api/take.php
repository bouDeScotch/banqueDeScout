<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../routes/check_right.php';
$token = $_GET['token'] ?? null;
if (!checkToken($token)) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "http_code" => 401,
        "message" => "Token invalide."
    ]);
    exit;
}

require_once __DIR__ . '/../routes/take_route.php';
$ID = $_GET['ID'] ?? null; // Get the ID from the request
$takingID = $_GET['takingID'] ?? null; // Get the takingID from the request
$amount = $_GET['amount'] ?? null; // Get the amount from the request
$companyId = $_GET['companyId'] ?? null; // Get the companyId from the request
$response = takeFromClient($ID, $takingID, $amount, $companyId); // Call the function to process the transaction

if ($response === true) {
    echo json_encode([
        "success" => true,
        "http_code" => 200,
        "message" => "Base mise à jour avec succès.",
        "updated_users" => count($users)
    ]);
} else {
    http_response_code(401);
    echo json_encode($response);
}
?>
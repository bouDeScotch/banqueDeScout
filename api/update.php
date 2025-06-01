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

require_once __DIR__ . '/../routes/update_route.php';
$response = updateClients();

if ($response === true) {
    echo json_encode([
        "success" => true,
        "http_code" => 200,
        "message" => "Base mise à jour avec succès.",
        "updated_users" => count($users)
    ]);
} else {
    http_response_code($response['http_code']);
    echo json_encode($response);
}
?>
<?php
function generateTakingId() {
    $characters = '0123456789ABCDEFGHJKLMNPRSTUVWXYZ';
    $blockSize = 4;
    $blockCount = 4;
    $takingId = '';
    for ($i = 0; $i < $blockCount; $i++) {
        for ($j = 0; $j < $blockSize; $j++) {
            $takingId .= $characters[rand(0, strlen($characters) - 1)];
        }
        if ($i < $blockCount - 1) {
            $takingId .= '-';
        }
    }
    return $takingId;
}

session_start();
if (!isset($_SESSION["connected"]) || $_SESSION["connected"] !== true) {
    header('Location: ../public/login.php');
    exit;
}

// Load user data from JSON file
$users = json_decode(file_get_contents(__DIR__ . '/../data/clients.json'), true);
foreach ($users as &$loop_user) {
    if ($loop_user["ID"] == $_SESSION["ID"]) {
        $user = &$loop_user;
        break;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $takingId = generateTakingId();
    // $user["takingIDs"] is an array of taking IDs
    $user["takingIDs"][] = $takingId;

    // Save updated user data back to JSON file
    file_put_contents(__DIR__ . '/../data/clients.json', json_encode($users, JSON_PRETTY_PRINT));

    // Redirect to the handle page
    header('Location: ../public/handle.php');
    exit;
} else {
    // If the request method is not POST, redirect to the handle page
    header('Location: ../public/handle.php');
    exit;
}
?>
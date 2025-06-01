<?php
function deleteTakingId($idIdx) {
    $idIdx = intval($idIdx);
    // Load user data from JSON file
    $users = json_decode(file_get_contents(__DIR__ . '/../data/clients.json'), true);
    foreach ($users as &$loop_user) {
        if ($loop_user["ID"] == $_SESSION["ID"]) {
            $user = &$loop_user;
            break;
        }
    }

    // Remove the taking ID from the user's list
    unset($user["takingIDs"][$idIdx]);

    // Save updated user data back to JSON file
    file_put_contents(__DIR__ . '/../data/clients.json', json_encode($users, JSON_PRETTY_PRINT));
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
    if (isset($_POST["idIdx"])) {
        $idIdx = $_POST["idIdx"];
        deleteTakingId($idIdx);

        // Redirect to the handle page
        header('Location: ../public/handle.php');
        exit;
    } else {
        // Handle the case where idIdx is not set
        echo "Error: Taking ID index not set.";
        exit;
    }
} else {
    // If the request method is not POST, redirect to the handle page
    header('Location: ../public/handle.php');
    exit;
}
?>
<?php
function takeFromClient($ID, $takingID, $amount, $companyId) {
    $clientsPath = __DIR__ . '/../data/clients.json';
    $users = json_decode(file_get_contents($clientsPath), true);

    $userAccount = null;
    $companyAccount = null;
    // Find the user with the given ID
    foreach ($users as &$user) {
        if ($user["ID"] == $ID) {
            $userAccount = &$user;
        }
        if ($user["ID"] == $companyId) {
            $companyAccount = &$user;
        }
    }
    if ($userAccount === null) {
        return false; // User not found}
    }

    // Check if the taking ID is valid
    if (!in_array($takingID, $userAccount["takingIDs"])) {
        return false; // Invalid taking ID}
    }

    // Check if the user has enough balance
    if ($userAccount["balance"] < $amount) {
        return false; // Insufficient balance
    }

    // Deduct the amount from the user's balance
    $userAccount["balance"] -= $amount;
    // Place the amount in the company's account
    $companyAccount["balance"] += $amount;

    // Save the updated user data back to the JSON file
    file_put_contents($clientsPath, json_encode($users, JSON_PRETTY_PRINT));
    return true; // Transaction successful
}
?>
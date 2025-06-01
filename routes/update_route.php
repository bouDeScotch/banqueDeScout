<?php
function updateClients() {
    $clientsPath = __DIR__ . '/../data/clients.json';
    $users = json_decode(file_get_contents($clientsPath), true);

    function differenceEnJours($inputDate, $date2) {
        $date1 = DateTime::createFromFormat('Y/m/d', $inputDate);
        return $date1 ? (int)$date2->diff($date1)->format('%R%a') : 0;
    }

    $today = new DateTime();
    foreach ($users as &$user) {
        $days = -differenceEnJours($user["LastTransactionDate"], $today);
        $user["Amount"] = (int)($user["Amount"] * ($user["DailyRate"] ** $days));
        $user["LastTransactionDate"] = date("Y/m/d");

        # If the user doesn't have a takingIDs, we set it to []
        if (!isset($user["takingIDs"])) {
            $user["takingIDs"] = [];
        }
    }
    unset($user);

    if (file_put_contents($clientsPath, json_encode($users, JSON_PRETTY_PRINT)) === false) {
        return [
            "success" => false,
            "http_code" => 500,
            "message" => "Erreur lors de la sauvegarde du fichier.",
        ];
    }

    return [
        "success" => true,
        "http_code" => 200,
        "message" => "Base mise à jour avec succès.",
        "updated_users" => count($users)
    ];
}
?>
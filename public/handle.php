<?php session_start();
if (!isset($_SESSION["connected"]) || $_SESSION["connected"] !== true) {
    header('Location: login.php');
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/icon.png">
    <title>Banque De Scout - Prelevement</title>
</head>
<body>
    <?php include 'nav.php'; ?>
    <h1>Gerer les prelevements</h1>
    <p>Sur cette page vous pouvez gerer qui peux vous prelevez de l'argent. Vous pouvez donner un de vos codes de prelevements a une entreprise, pour lui permettre de vous prendre directement de l'argent.</p>
    <?php
    if ($user["takingIDs"] == []) {
        echo "<p>Vous n'avez pas encore de prelevement actif.</p>";
    } else {
        echo "<h2>Vos prelevements actifs</h2>";
        echo "<ul>";
        foreach ($user["takingIDs"] as $i => $takingID) {
            echo "<li>
            {$takingID} | 
                <form action=\"../routes/removeTakingId.php\" method=\"post\">
                    <input type=\"hidden\" name=\"idIdx\" value=\"{$i}\">
                    <input type=\"submit\" value=\"Supprimer\">
                </form>
            </li>";
        }
        echo "</ul>";
    }
    ?>
    <h2>Ajouter un prelevement</h2>
    <p>Pour ajouter un prelevement, vous devez d'abord le creer. Vous pouvez le faire en cliquant sur le bouton ci-dessous.</p>
    <form action="../routes/createTakingId.php" method="post">
        <input type="submit" value="Creer un prelevement">
    </form>
</body>
</html>
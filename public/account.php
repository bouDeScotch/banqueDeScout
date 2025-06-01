<?php session_start();
if (!isset($_SESSION["connected"]) || $_SESSION["connected"] !== true) {
    header('Location: login.php');
    exit;
}

include_once __DIR__ . '/../routes/update_route.php';
updateClients();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/icon.png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Banque De Scout</title>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="mainData">
        <?php 
            foreach ($user as $key => $value) {
                if ($key == "DailyRate") {
                    $value = ((float)$value - 1) * 100;
                    $value = number_format($value, 2, '.', '') . "%";
                }
                if ($key == "Amount" || $key == "DailyRate" || $key == "Username") {
                    echo "<div class='line'>
                        <div class='key'>$key</div>
                        <div class='value'>$value</div>
                    </div>";
                }
            } 
        ?>
    </div>

    <div class="actions">
        <a href="transfer.php" class="action">Faire un virement</a>
        <a href="handle.php" class="action">Gerer les prelevement</a>
        <a href="../routes/logout.php" class="action">Se déconnecter</a>
    </div>
</body>
</html>
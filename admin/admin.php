<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/icon.png">
    <title>Banque De Scout - Page Admin</title>
</head>
<body>
    <?php include 'nav.php'; ?>
    <ul>
        <li><a href="generate_temp_password.php">Generer les mots de passe temporaires</a></li>
        <li><a href="new_user.php">Ajouter un utilisateur</a></li>
        <li><a href="withdrawing.php">Prelever et ajouter argent</a></li>
    </ul>
</body>
</html>
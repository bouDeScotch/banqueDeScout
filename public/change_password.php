<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = "";
    $users = json_decode(file_get_contents(__DIR__ . '/../data/clients.json'), true);
    $ID = (int)$_POST['ID'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    foreach ($users as &$user) {
        if ($user['ID'] === $ID && password_verify($current_password, $user['PwdHash'])) {
            $user['PwdHash'] = password_hash($new_password, PASSWORD_BCRYPT);
            $user['temp'] = false; // Remove temporary password status

            if (file_put_contents(__DIR__ . '/../data/clients.json', json_encode($users, JSON_PRETTY_PRINT)) === false) {
                $error .= "Erreur lors de la sauvegarde du fichier.";
            } else {
                // Redirect to login page after successful password change
                header('Location: login.php');
                exit;
            }
        }
    }

    $error .= "Identifiants invalides.";
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Banque De Scout - Choisir mot de passe</title>
</head>
<body>
<p>Si vous etes sur cette page, c'est que vous avez reçu un mot de passe temporaire, et que vous voulez en changer</p>
<form method="post" class="login-form">
    <input type="number" name="ID" placeholder="ID" required>
    <input type="password" name="current_password" placeholder="Mot de passe temporaire" required>
    <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
    <button type="submit">Changer de mot de passe</button>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</form>
</body>
</html>
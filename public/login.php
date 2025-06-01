<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = json_decode(file_get_contents(__DIR__ . '/../data/clients.json'), true);
    $ID = (int)$_POST['ID'];
    $password = $_POST['password'];

    foreach ($users as $user) {
        if ($user['ID'] === $ID && password_verify($password, $user['PwdHash'])) {
            // Check if the password is temporary, and if so, redirect to change password page
            $_SESSION['ID'] = $ID;
            if ($user['temp']) {
                header('Location: change_password.php');
            } else {
                $_SESSION["connected"] = true;
                session_write_close();
                header('Location: account.php');
            }
            exit;
        }
    }

    $error = "Identifiants invalides." . $ID . $password;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/icon.png">
    <title>Banque De Scout - Login</title>
</head>
<body>
<?php include 'nav.php'; ?>
<form method="post" class="login-form">
    <input type="number" name="ID" placeholder="ID" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
    <p>Pas encore de compte ? <a href="register.php">Inscris-toi ici</a></p>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</form>
</body>
</html>
<?php
session_start();

// Si le mot de passe a été envoyé et est correct, on le sauvegarde dans la session
if (!isset($_SESSION['acces_autorise']) || $_SESSION['acces_autorise'] !== true) {
    include_once __DIR__ . '/../routes/check_right.php';
    $check = checkAdmin($_POST['password'] ?? null);
    $_SESSION['acces_autorise'] = $check["success"];
}
// Si autorisé et formulaire utilisateur soumis
if (isset($_SESSION['acces_autorise']) && $_SESSION['acces_autorise'] === true
    && isset($_POST['Username'], $_POST['Balance'], $_POST['DailyRate'])) {

    $users = json_decode(file_get_contents(__DIR__ . '/../data/clients.json'), true);
    end($users);
    $lastKey = key($users);
    $lastUser = $users[$lastKey];

    $ID = $lastUser["ID"] + 1;
    $newUser = $lastUser;
    $newUser["ID"] = $ID;
    $newUser['Username'] = $_POST['Username'];
    $newUser['Amount'] = (int)$_POST['Balance'];
    if ($newUser['Amount'] >= 100) {
        $newUser['Amount'] += 50;
    }
    $newUser['DailyRate'] = (float)$_POST['DailyRate'];
    $newUser['LastTransactionDate'] = date("Y/m/d");
    $newUser['PwdHash'] = "NotSet";
    $newUser["temp"] = true;
    $newUser["takingIDs"] = [];

    $users[] = $newUser;

    if (file_put_contents(__DIR__ . '/../data/clients.json', json_encode($users, JSON_PRETTY_PRINT))) {
        header('Location: generate_temp_password.php?ID=' . $ID);
        exit;
    } else {
        die('Erreur lors de la sauvegarde du fichier.');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page protégée</title>
</head>
<body>
<?php if (!isset($_SESSION['acces_autorise']) || $_SESSION['acces_autorise'] !== true): ?>
    <h2>Mot de passe requis</h2>
    <form method="POST">
        <input type="password" name="password" placeholder="Mot de passe">
        <button type="submit">Valider</button>
    </form>
    <?php if (isset($_POST['password']) && $_POST['password'] !== $motDePasseCorrect): ?>
        <p style="color:red;">Mot de passe incorrect.</p>
    <?php endif; ?>
<?php else: ?>
    <?php include 'nav.php'; ?>
    <h2>Ajouter un utilisateur</h2>
    <form method="POST">
        <input type="text" name="Username" placeholder="Username" required>
        <input type="number" name="Balance" placeholder="Balance" required>
        <input type="number" step="0.01" name="DailyRate" placeholder="DailyRate" value="1.01" required>
        <button type="submit">Ajouter un utilisateur</button>
    </form>
<?php endif; ?>
</body>
</html>

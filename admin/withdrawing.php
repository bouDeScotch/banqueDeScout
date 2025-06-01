<?php
session_start();

// Si le mot de passe a été envoyé et est correct, on le sauvegarde dans la session
if (!isset($_SESSION['acces_autorise']) || $_SESSION['acces_autorise'] !== true) {
    include_once __DIR__ . '/../routes/check_right.php';
    $check = checkAdmin($_POST['password'] ?? null);
    $_SESSION['acces_autorise'] = $check["success"];
}
// Si autorisé et formulaire utilisateur soumis
if (isset($_SESSION['acces_autorise']) && $_SESSION['acces_autorise'] === true) {
    $users = json_decode(file_get_contents(__DIR__ . '/../data/clients.json'), true);
    if (isset($_POST["ID"]) && isset($_POST["Amount"])) {
        $ID = (int)$_POST["ID"];
        $Amount = (int)$_POST["Amount"];

        foreach ($users as &$loop_user) {
            if ($loop_user["ID"] == $ID) {
                $loop_user["Amount"] += $Amount;
                break;
            }
        }

        if (file_put_contents(__DIR__ . '/../data/clients.json', json_encode($users, JSON_PRETTY_PRINT)) === false) {
            echo "Erreur lors de la sauvegarde du fichier.";
        } else {
            header('Location: admin.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="../assets/icon.png">
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
    <h2>Prelever/ajouter</h2>
    <form method="POST">
        <input type="number" name="ID" placeholder="ID" required>
        <input type="number" name="Amount" placeholder="Montant" required>
        <p>(Montant positif pour ajouter de l'argent et negatif pour en retirer)</p>
        <button type="submit">Ajouter un utilisateur</button>
    </form>

    <div class="idNames">
        <h3>Liste des utilisateurs</h3>
        <ul>
            <?php
            foreach ($users as $loop_user) {
                echo "<li class='user-item' data-id='{$loop_user['ID']}'>ID: {$loop_user['ID']} - Nom: {$loop_user['Username']} - Amount: {$loop_user['Amount']}</li>";
            }
            ?>
        </ul>
    </div>
<?php endif; ?>
</body>
</html>

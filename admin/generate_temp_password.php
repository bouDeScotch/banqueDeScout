<?php
session_start();

// Si le mot de passe a été envoyé et est correct, on le sauvegarde dans la session

if (!isset($_SESSION['acces_autorise']) || $_SESSION['acces_autorise'] !== true) {
    include_once __DIR__ . '/../routes/check_right.php';
    $check = checkAdmin($_POST['password'] ?? null);
    $_SESSION['acces_autorise'] = $check["success"];
}
// Si on n'a pas encore le bon mot de passe, on affiche le formulaire
if (!isset($_SESSION['acces_autorise']) || $_SESSION['acces_autorise'] !== true):
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Page protégée</title>
    </head>
    <body>
        <h2>Mot de passe requis</h2>
        <form method="POST">
            <input type="password" name="password" placeholder="Mot de passe">
            <button type="submit">Valider</button>
        </form>
        <?php
        if (isset($_POST['password']) && !$check["success"]) {
            echo "<p style='color:red;'>Mot de passe incorrect.</p>";
            var_dump($check);
        }
        ?>
    </body>
    </html>
<?php
// Sinon, on affiche la page normalement
else:
?>
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/icon.png">
    <link rel="stylesheet" href="style.css">
        <title>Contenu Secret</title>
    </head>
    <body>
        <?php include 'nav.php'; ?>
        <?php   
        // Inclure le fichier de navigation
        if (isset($_SESSION['acces_autorise']) && $_SESSION['acces_autorise'] === true) {
            if (isset($_POST['ID'])) {
                $ID = (int)$_POST['ID'];
                $users = json_decode(file_get_contents(__DIR__ . '/../data/clients.json'), true);
                $userFound = false;
        
                foreach ($users as &$user) {
                    if ($user['ID'] === $ID) {
                        $userFound = true;
                        // Générer un mot de passe temporaire
                        $tempPassword = bin2hex(random_bytes(4)); // 8 caractères aléatoires
                        $user['PwdHash'] = password_hash($tempPassword, PASSWORD_BCRYPT);
                        $user['temp'] = true; // Marquer comme mot de passe temporaire
                        if (file_put_contents(__DIR__ . '/../data/clients.json', json_encode($users, JSON_PRETTY_PRINT)) === false) {
                            echo "Erreur lors de la sauvegarde du fichier.";
                        } else {
                            // Envoi d'un email (simulé ici par un echo)
                            // mail($user['email'], "Mot de passe temporaire", "Votre mot de passe temporaire est : $tempPassword");
                            echo "Un email a été envoyé à {$user['email']} avec le mot de passe temporaire.";
                        }
                        echo "Mot de passe temporaire généré : $tempPassword";
                        break;
                    }
                }
        
                if (!$userFound) {
                    echo "Utilisateur non trouvé.";
                }
            }
        }
        ?>

        <form method="POST">
            <input type="number" name="ID" placeholder="ID" required>
            <button type="submit">Générer un mot de passe temporaire</button>
        </form>
    </body>
    </html>
<?php endif; ?>
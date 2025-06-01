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

function differenceEnJours($inputDate) {
    $date1 = DateTime::createFromFormat('Y/m/d', $inputDate);
    $date2 = new DateTime(); // aujourd'hui

    // Vérifie que la date d'entrée est valide
    if (!$date1) {
        return "Date invalide.";
    }

    // Calcul de la différence
    $interval = $date2->diff($date1);

    // Retourne la différence en jours, avec signe (positif = futur, négatif = passé)
    return (int)$interval->format('%R%a');
}
$days = -differenceEnJours($user["LastTransactionDate"]);
$user["Amount"] = (int)$user["Amount"] * ((float)$user["DailyRate"] ** $days);
$user["Amount"] = (int)$user["Amount"];
$user["LastTransactionDate"] = date("Y/m/d");

if (file_put_contents(__DIR__ . '/../data/clients.json', json_encode($users, JSON_PRETTY_PRINT)) === false) {
    echo "Erreur lors de la sauvegarde du fichier.";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['amount']) && isset($_POST['recipientID'])) {
        $amount = (int)$_POST['amount'];
        $recipientID = (int)$_POST['recipientID'];

        // Check if the recipient exists
        $recipient = null;
        foreach ($users as &$loop_user) {
            if ($loop_user["ID"] == $recipientID) {
                $recipient = &$loop_user;
                break;
            }
        }

        if ($recipient) {
            // Check if the user has enough balance
            if ($user["Amount"] >= $amount && $amount > 0) {
                // Perform the transfer
                $user["Amount"] -= $amount;
                $recipient["Amount"] += $amount;

                // Update the last transaction date for both users
                $user["LastTransactionDate"] = date("Y/m/d");
                $recipient["LastTransactionDate"] = date("Y/m/d");

                // Save changes to JSON file
                if (file_put_contents(__DIR__ . '/../data/clients.json', json_encode($users, JSON_PRETTY_PRINT)) !== false) {
                    echo "<p>Virement de $amount ARO vers l'utilisateur ID $recipientID effectué avec succès.</p>";
                } else {
                    echo "<p>Erreur lors de la sauvegarde du fichier.</p>";
                }
            } else {
                echo "<p>Fonds insuffisants pour effectuer le virement.</p>";
            }
        } else {
            echo "<p>Destinataire non trouvé.</p>";
        }
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
    <title>Banque De Scout - Virement</title>
</head>
<body>
    <?php include 'nav.php'; ?>
    <h1>Virement</h1>
    <form method="POST" class="virement-form">
        <div class="mainData">
            <?php 
                foreach ($user as $key => $value) {
                    if ($key == "Amount" || $key == "DailyRate" || $key == "Username") {
                        echo "<div class='line'>
                            <div class='key'>$key</div>
                            <div class='value'>$value</div>
                        </div>";
                    }
                } 
            ?>
        </div>
        <input type="number" name="amount" placeholder="Montant à transférer" required>
        <input type="number" name="recipientID" id="recipientID" placeholder="ID du destinataire" required>
        <button type="submit">Transférer</button>
    </form>

    <div class="idNames">
        <h3>Liste des utilisateurs</h3>
        <ul>
            <?php
            foreach ($users as $loop_user) {
                if ($loop_user["ID"] != $_SESSION["ID"]) {
                    echo "<li class='user-item' data-id='{$loop_user['ID']}'>ID: {$loop_user['ID']} - Nom: {$loop_user['Username']}</li>";
                }
            }
            ?>
        </ul>
    </div>

    <script>
    document.querySelectorAll('.user-item').forEach(item => {
        item.addEventListener('click', () => {
            const recipientID = item.getAttribute('data-id');
            document.getElementById('recipientID').value = recipientID;
        });
    });
    </script>
</body>
</html>
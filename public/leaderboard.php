<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/icon.png">
    <title>Banque De Scout</title>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="idNames">
        <h3>Liste des utilisateurs</h3>
        <ul>
            <?php
            $users = json_decode(file_get_contents(__DIR__ . '/../data/clients.json'), true);
            // Sort users by Amount in descending order
            usort($users, function($a, $b) {
                return $b['Amount'] <=> $a['Amount']; // tri descendant
            });


            $i = 0;
            foreach ($users as $loop_user) {
                if ($i == 0) {
                    echo '';
                } else { // Skip the first user (admin so richest)
                    echo "<li class='user-item'>$i - {$loop_user['Amount']} ARO</li>";
                }
                $i++;
            }
            ?>
        </ul>
    </div>
</body>
</html>
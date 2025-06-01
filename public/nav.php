<nav>
        <div id="navLogo"><a href="index.php">Banque De Scout</a></div>
        <?php
        if (isset($_SESSION["connected"]) && $_SESSION["connected"] === true) {
            echo '<a href="account.php"><div id="logInButton">Mon Compte</div></a>';
        } else {
            echo '<a href="login.php"><div id="logInButton">Log In</div></a>';
        }
        ?>
</nav>

<a href="leaderboard.php"><div id="bottomLeftBonhomme"></div></a>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark mb-2">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Accueil</a>
        </li>
        <?php
        if (!isset($_SESSION['loggedin'])) {
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="inscription.php">Inscription </a>';
            echo '</li>';
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="connexion.php">Connexion </a>';
            echo '</li></ul>';
        }
        if (isset($_SESSION['loggedin'])) {
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="profil.php">Profil </a>';
            echo '</li>';
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="welcome.php">Mini-chat </a>';
            echo '</li></ul>';

            echo '<form action="logout.php" method="post">';
            echo '<input class="btn btn-primary" type="submit" name="deconnexion" role="button" value="Deconnexion"></input> ';
            echo '</form>';
        }
        ?>
</nav>
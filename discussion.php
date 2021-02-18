<?php
session_start();


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: connexion.php");
    exit;
}
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Discussion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Hachi+Maru+Pop&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <main class="container">
        <div class="page-header">
            <h1>Salut, <b><?php echo htmlspecialchars($_SESSION["login"]); ?></b> Bienvenue sur discussion.</h1>
        </div>
        <div>
            <form method="post" action="">
                <div class="form-group green-border-focus">
                    <label for="exampleFormControlTextarea5">Ecrivez votre message ici</label>
                    <textarea class="form-control" id="exampleFormControlTextarea5" rows="3" type="text" name="message" placeholder="Message"></textarea>
                </div>
                <input type="submit" class="btn btn-success" name="envoyer" value="envoyer" />
                <?php
                if (isset($error_msg)) {
                    echo $error_msg;
                }
                ?>
            </form>
        </div>
        <?php
        if (isset($_POST['envoyer'])) {
            $message = $_POST['message'];
            $id = $_SESSION['id'];
            $date = date('Y.m.d H:i:s');

            if (!empty($_POST['message'])) {
                $connexion = new PDO("mysql:host=localhost;dbname=discussion", 'root', '');
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $requete = $connexion->prepare(
                    "INSERT INTO messages (message, id_utilisateur, date)
                                            VALUES(:mess, :id_utilisateur, :date)"
                );

                $requete->bindParam(':mess', $message);
                $requete->bindParam(':id_utilisateur', $id);
                $requete->bindParam(':date', $date);

                $requete->execute();
            } else {
                $error_msg = '<p class="error">Veuillez écrire votre message</p>';
            }
        }
        ?>
        <section>

            <?php
            $requete = $pdo->prepare(
                "SELECT login,message,date 
                                                FROM utilisateurs
                                                    INNER JOIN messages
                                                        ON utilisateurs.id = messages.id_utilisateur"
            );

            $requete->execute();

            $result = $requete->fetchAll();



            for ($i = 0; isset($result[$i]); $i++) {
                echo '</br><p class="message"> Envoyé par <b>' . $result[$i]['login'] . '</b> le <em>' . $result[$i]['date'] . '</em> ' . $result[$i]['message'] . '</p>';
            }
            ?>
        </section>
    </main>
    <?php
    include 'footer.php'
    ?>
</body>

</html>
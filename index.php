  <?php
    session_start();
    ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="style.css">
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Hachi+Maru+Pop&display=swap" rel="stylesheet">
      <title>Acceuil</title>
  </head>

  <body>
      <?php
        include 'header.php';
        ?>
      <main class="container">
          <?php
            if (isset($ligne)) {

                echo "bravo";
            } else {
            ?>
              <div class="row">
                  <div class="col-9">
                      <h1>Bienvenue sur mini-chat</h1>
                  </div>
                  <div class="col-4">
                      <h3><a href="connexion.php">Connectez-vous</a> pour commencer a discuter avec les mini-chatteurs !</h3>
                      <h3>Si vous n'avez pas encore de compte mini-chat...inscrivez-vous <a href="inscription.php">ICI !</a>
                  </div>
                  <div class="col-6"><img src="background.webp" alt="Dessein débat présidentielle US" height="400px" width="600px" />
                  </div>
              <?php
            }
                ?>
      </main>
      <?php
        include 'footer.php';
        ?>
  </body>

  </html>
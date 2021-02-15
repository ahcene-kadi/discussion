<?php
// Initialize the session
session_start();

// Vérification de connexion de l'utilisateur, si oui redirection sur la page d'index
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: welcome.php");
  exit;
}

// Inclusion du fichier config
require_once "config.php";

// Création des variables et initialisation des valeurs
$login = $password = "";
$login_err = $password_err = "";

// Traitement des données du formulair lors de l'envoie du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Si le login est pas rempli
  if (empty(trim($_POST["login"]))) {
    $login_err = "Please enter username.";
  } else {
    $login = trim($_POST["login"]);
  }

  // Si le password est pas rempli
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter your password.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Si les données sont bien rempli
  if (empty($login_err) && empty($password_err)) {
    // Prepare a select statement
    $sql = "SELECT id, login, password FROM utilisateurs WHERE login = :login";

    if ($stmt = $pdo->prepare($sql)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);

      // Set parameters
      $param_login = trim($_POST["login"]);

      // Attempt to execute the prepared statement
      if ($stmt->execute()) {
        // Check if username exists, if yes then verify password
        if ($stmt->rowCount() == 1) {
          if ($row = $stmt->fetch()) {
            $id = $row["id"];
            $login = $row["login"];
            $hashed_password = $row["password"];
            if (password_verify($password, $hashed_password)) {
              // Password is correct, so start a new session
              session_start();

              // Store data in session variables
              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["login"] = $login;

              // Redirect user to welcome page
              header("location: welcome.php");
            } else {
              // Display an error message if password is not valid
              $password_err = "The password you entered was not valid.";
            }
          }
        } else {
          // Display an error message if username doesn't exist
          $login_err = "No account found with that username.";
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      unset($stmt);
    }
  }

  // Close connection
  unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Hachi+Maru+Pop&display=swap" rel="stylesheet">
</head>

<body>
  <?php
  include 'header.php';
  ?>
  <div class="wrapper">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group <?php echo (!empty($login_err)) ? 'has-error' : ''; ?>">
        <label>Username</label>
        <input type="text" name="login" class="form-control" value="<?php echo $login; ?>">
        <span class="help-block"><?php echo $login_err; ?></span>
      </div>
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
        <span class="help-block"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Login">
      </div>
      <p>Don't have an account? <a href="inscription.php">Sign up now</a>.</p>
    </form>
  </div>
  <?php
  include 'footer.php';
  ?>
</body>

</html>
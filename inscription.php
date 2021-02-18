<?php
require_once "config.php";

$login = $password = $confirm_password = "";
$login_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["login"]))) {
    $login_err = "Please enter a username.";
  } else {
    $sql = "SELECT id FROM utilisateurs WHERE login = :login";

    if ($stmt = $pdo->prepare($sql)) {
      $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);

      $param_login = trim($_POST["login"]);

      if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
          $login_err = "This username is already taken.";
        } else {
          $login = trim($_POST["login"]);
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }

      unset($stmt);
    }
  }


  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter a password.";
  } elseif (strlen(trim($_POST["password"])) < 6) {
    $password_err = "Password must have atleast 6 characters.";
  } else {
    $password = trim($_POST["password"]);
  }


  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Please confirm password.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "Password did not match.";
    }
  }

  if (empty($login_err) && empty($password_err) && empty($confirm_password_err)) {

    $sql = "INSERT INTO utilisateurs (login, password) VALUES (:login, :password)";

    if ($stmt = $pdo->prepare($sql)) {
      $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);
      $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

      $param_login = $login;
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      if ($stmt->execute()) {
        header("location: connexion.php");
      } else {
        echo "Something went wrong. Please try again later.";
      }

      unset($stmt);
    }
  }

  unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Hachi+Maru+Pop&display=swap" rel="stylesheet">
  </style>
</head>

<body>
  <?php
  include 'header.php';
  ?>
  <div class="wrapper">
    <h2>Inscription</h2>
    <p>Inscrivez-vous en remplissant le formulaire.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group <?php echo (!empty($login_err)) ? 'has-error' : ''; ?>">
        <label>Login</label>
        <input type="text" name="login" class="form-control" value="<?php echo $login; ?>">
        <span class="help-block"><?php echo $login_err; ?></span>
      </div>
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
        <label>Password</label>
        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
        <span class="help-block"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
        <label>Confirmez le Password</label>
        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
        <span class="help-block"><?php echo $confirm_password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Valider">
        <input type="reset" class="btn btn-default" value="Reset">
      </div>
      <p>Vous avez déjà un compte? <a href="connexion.php">Connectez-vous</a>.</p>
    </form>
  </div>
  <?php
  include 'footer.php';
  ?>
</body>

</html>
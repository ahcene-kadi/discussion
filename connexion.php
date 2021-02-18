<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: discussion.php");
  exit;
}

require_once "config.php";

$login = $password = "";
$login_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["login"]))) {
    $login_err = "Entrez un login.";
  } else {
    $login = trim($_POST["login"]);
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "Entrez un password.";
  } else {
    $password = trim($_POST["password"]);
  }

  if (empty($login_err) && empty($password_err)) {
    $sql = "SELECT id, login, password FROM utilisateurs WHERE login = :login";

    if ($stmt = $pdo->prepare($sql)) {
      $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);

      $param_login = trim($_POST["login"]);

      if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
          if ($row = $stmt->fetch()) {
            $id = $row["id"];
            $login = $row["login"];
            $hashed_password = $row["password"];
            if (password_verify($password, $hashed_password)) {

              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["login"] = $login;

              header("location: discussion.php");
            } else {
              $password_err = "Password incorrect.";
            }
          }
        } else {
          $login_err = "Aucun compte ne correspond a votre login.";
        }
      } else {
        echo "Oops! Un problème est survenu. Veuillez réessayer plus tard.";
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
  <title>Connexion</title>
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
    <h2>Connexion</h2>
    <p>Connectez-vous a votre compte.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group <?php echo (!empty($login_err)) ? 'has-error' : ''; ?>">
        <label>Login</label>
        <input type="text" name="login" class="form-control" value="<?php echo $login; ?>">
        <span class="help-block"><?php echo $login_err; ?></span>
      </div>
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
        <span class="help-block"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Valider">
      </div>
      <p>Vous n'avez pas de compte? <a href="inscription.php">Inscrivez-vous maintenant</a>.</p>
    </form>
  </div>
  <?php
  include 'footer.php';
  ?>
</body>

</html>
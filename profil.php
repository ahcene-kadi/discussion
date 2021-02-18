<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: connexion.php");
  exit;
}

require_once "config.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["new_password"]))) {
    $new_password_err = "Entrez le nouveau password.";
  } elseif (strlen(trim($_POST["new_password"])) < 6) {
    $new_password_err = "Votre password doit contenir au moins 6 caractères.";
  } else {
    $new_password = trim($_POST["new_password"]);
  }

  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Confirmez votre password.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($new_password_err) && ($new_password != $confirm_password)) {
      $confirm_password_err = "Les password ne correspondent pas.";
    }
  }

  if (empty($new_password_err) && empty($confirm_password_err)) {
    $sql = "UPDATE utilisateurs SET password = :password WHERE id = :id";

    if ($stmt = $pdo->prepare($sql)) {
      $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
      $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

      $param_password = password_hash($new_password, PASSWORD_DEFAULT);
      $param_id = $_SESSION["id"];

      if ($stmt->execute()) {
        session_destroy();
        header("location: connexion.php");
        exit();
      } else {
        echo "Oops! Something went wrong. Please try again later.";
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
  <title>Profil</title>
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
    <h2>Réinitialisation de votre mot de passe</h2>
    <p>Remplissez le formulaire pour changer le mot de passe.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
        <label>Nouveau Password</label>
        <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
        <span class="help-block"><?php echo $new_password_err; ?></span>
      </div>
      <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
        <label>Confirmez Password</label>
        <input type="password" name="confirm_password" class="form-control">
        <span class="help-block"><?php echo $confirm_password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Modifier">
        <a class="btn btn-link" href="discussion.php">Annuler</a>
      </div>
    </form>
  </div>
  <?php
  include 'footer.php';
  ?>
</body>

</html>
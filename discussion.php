<?php
session_start();


if (!empty($_POST['deco'])) {
	unset($_SESSION['login']);
	unset($_SESSION['password']);
	unset($_SESSION['profil']);
}
$db = mysqli_connect("localhost", "root", "", "discussion");
$query = "SELECT login, date, id_utilisateur, message FROM `utilisateurs` ,`messages` WHERE utilisateurs.id = id_utilisateur ORDER BY `messages`.`id` ASC";
$result = mysqli_query($db, $query);
$query1 = "SELECT id, message FROM `messages`";
$result1 = mysqli_query($db, $query1);


?>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="index.css" />
	<title>Discussion</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Hachi+Maru+Pop&display=swap" rel="stylesheet">
	<title>Discussion</title>
</head>

<body class="discussion">
	<?php
	include 'header.php';
	?>
	<?php
	if ((isset($_SESSION['login'])) && (isset($_SESSION['password']))) {

	?>






	<?php
	} else {
	?>




	<?php
	}
	if (!empty($_POST['deco'])) {
		unset($_SESSION['login']);
		unset($_SESSION['password']);
	}

	?>

	</header>

	<article class="espacecommentaire">
		<table>
			<tr>
				<td><strong>Utilisateur(s)</strong></td>
				<td><strong>Messages</strong></td>
			</tr>

			<?php
			while (($row = mysqli_fetch_array($result)) && ($row1 = mysqli_fetch_array($result1))) {
			?>
				<tr>
					<td><?php echo "Posté par : ";
						echo "<strong>" . $row['login'] . "</strong>";
						echo " le ";
						echo $row['date']; ?></td>
					<td><?php echo $row['message']; ?></td>

					<?php

					if (isset($_SESSION['login'])) {
						if (($_SESSION['login'] == $row['login']) || ($_SESSION['login'] == "admin")) {
					?>
							<td>
								<form method="post">
									<input type="submit" name="effacer" value="Supprimer">
									<input type="hidden" name="moi" value="<?php echo $row1['id'] ?>">
								</form>
					<?php
						}
						if (isset($_POST['effacer'])) {
							$message = $_POST['moi'];
							$query2 = "DELETE FROM `messages` WHERE messages . id = '$message'";
							$result2 = mysqli_query($db, $query2);
							$_SESSION['delete'] = true;
						}
					}
				}
					?>
							</td>
				</tr>


				<?php

				if (!isset($_SESSION['login'])) {
					header('Location: connexion.php');
				}

				$login = $_SESSION['login'];

				$requeteid = "SELECT id FROM utilisateurs WHERE login='$login'";
				$query = mysqli_query($db, $requeteid);
				$id = mysqli_fetch_array($query);



				?>



				<div class="ajtcomm">
					<h2>Ajoutez votre messages:</h2>

					<form class="formulaire1" name="inscription" method="post" action="discussion.php">

						Votre commentaire: <br><textarea name="message"></textarea></br>

						<input type="submit" name="valider" value="OK" />
					</form>
					<?php

					if (isset($_POST['valider'])) {


						$message = $_POST['message'];
						$id = $id['id'];
						$time = "SELECT date FROM messages, utilisateurs WHERE id_utilisateur='$id' ORDER BY messages.id DESC ";
						$req2 = mysqli_query($db, $time);
						$req3 = mysqli_fetch_array($req2);
						$req4 = mysqli_num_rows($req2);

						if ($req4 > 0) {
							date_default_timezone_set('Europe/Paris');
							$date1 = date_create(date("Y-m-d H:i:s"));
							$date2 = date_create($req3['date']);


							if (date_timestamp_get($date1) - date_timestamp_get($date2) < 10) {

								echo "Veuillez attendre au moins 10 secondes";
							} else {
								$requete = "INSERT INTO `messages` (`id`, `message`, `id_utilisateur`, `date`) VALUES (NULL, '$message', '$id', CURRENT_TIMESTAMP())";
								mysqli_query($db, $requete);
								header('location: discussion.php');
							}
						} else {
							$requete = "INSERT INTO `messages` (`id`, `message`, `id_utilisateur`, `date`) VALUES (NULL, '$message', '$id', CURRENT_TIMESTAMP())";
							mysqli_query($db, $requete);
							header('location: discussion.php');
						}
					}

					?>
					<?php

					mysqli_close($db);
					?>
		</table>
	</article>



	<?php



	?>
</body>

</html>
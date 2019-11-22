<?php
session_start();
include("outils/outils.php");
include("outils/base.php");
?>
<!DOCTYPE HTML>
<?php include("structelem/head.php");?>
	<body>
	<?php include('structelem/menu.php');?>
		<main>
		<?php 
		// Messages d'erreurs à la connexion ; tentative de fraude ; déconnexion ; inscription réussie
		if(isset($_GET['msg'])){
			if ($_GET['msg']=="err"){
				echo "<section class='alertbox'><strong>Login ou mot de passe incorrect. Veuillez recommencer.</strong></section>\n";}
			if ($_GET['msg']=="noauth"){
				echo "<section class='alertbox'><strong>Vous n'avez pas accès à cette page, veuillez d'abord vous connecter</strong></section>\n";}
			if ($_GET['msg']=="logout"){
				echo "<section class='infobox'><strong>Vous êtes bien déconnecté</strong></section>\n";}
				if ($_GET['msg']=="insc_success"){
					echo "<section class='infobox'><strong>Vous êtes inscrit! Vous pouvez vous connecter</strong></section>\n";}
		}
		?>
		
		<?php
		// Connexion ----------------------------------------------------------------------------------
		
		if (!isset($_SESSION['auth']) || ($_SESSION['auth']==FALSE)){
		echo"<section class='loginformcont float'>\n
			<h2 class='littlemargin' style='border:0'>Connecte toi !</h2><br>\n
			<form method='POST' action='./login.php'>\n
				<label for='mail'>Adresse Mail </label>\n
					<input type='text' name='mail' id='mail' required><br>\n
				<label for='passwd'>Mot de passe </label>\n
					<input type='password' name='passwd' id='passwd' required><br>\n
				<input type='submit' value='Connexion' class='buttonimp'><br>\n
			</form>\n
		</section>\n";
		}
		else {echo "<section><div class='float'>Bienvenue {$_SESSION['prénom']}! Tu es connecté</div></section>";}
		?>
		<section class="yellowbox float">
		<strong>Trombinouc</strong> c'est vraiment génial, inscris-toi (stp). <br>
		</section>
		<div class='float'>
		<script charset='UTF-8' src='http://www.meteofrance.com/mf3-rpc-portlet/rest/vignettepartenaire/061520/type/VILLE_FRANCE/size/PAYSAGE_VIGNETTE' type='text/javascript'>
		</script>
		</div>
		<br>
		
		<?php
		// Inscription ----------------------------------------------------------------------------------
				
				//Messages d'erreur à l'inscription
				
		if(isset($_GET['msg'])){
			if ($_GET['msg']=="passwd"){
				echo "<section class='alertbox float' style='width:45%'><strong>Les mots de passe ne sont pas identiques</strong></section>\n";}
			if ($_GET['msg']=="mail"){
				echo "<section class='alertbox float' style='width:45%'><strong>Ce mail est déjà utilisé</strong></section>\n";}
		}
		
				//Formulaire d'inscription
				
		if (!isset($_SESSION['auth']) || ($_SESSION['auth']==FALSE)){
			echo"<section class='signupform clear'>\n
			<h2 class='littlemargin' style='border:0'>Inscription</h2><br>\n
			<form method='POST' action='./signup.php'>\n
				<p><label for='mail_insc'>Adresse Mail </label>\n
					<input type='text' name='mail_insc' id='mail_insc' required></p>\n
				<p><label for='prenom'>Prénom </label>\n
					<input type='text' name='prenom' id='prenom' required></p>\n
				<p><label for='Nom'>Nom </label>\n
					<input type='text' name='nom' id='nom' required></p>\n
				<p><label for='dateAnniv'>Date de naissance </label>
					<input type='date' name='dateAnniv' id='dateAnniv'></p>\n
				<p><label for='passwd_insc'>Mot de passe </label>\n
					<input type='password' name='passwd_insc' id='passwd_insc' required></p>\n
				<p><label for='mdpconf'>Confirmez le mot de passe </label>\n
					<input type='password' name='passwd_conf' id='passwd_conf' required></p>\n
				<input type='submit' value='Inscription!' class='buttonimp'>\n
			</form>\n
		</section>\n";
		}
		?>
		
		<!--Fin contenu -->
		<?php include('structelem/footer.php')?>
	</main>	
	</body>
</html>


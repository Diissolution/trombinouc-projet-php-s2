<?php
session_start();
include("outils/authcheck.php");
include("outils/outils.php");
include("outils/base.php");
$numUtil=$_SESSION['numUtil'];

/* Récupère publications avec nom prénom auteur */
$sql = "SELECT prénom,nom,_auteur,datePub,heurePub,textePub,numPub,_utilisateurPage,img,numUtil FROM PUBLICATIONS 
INNER JOIN UTILISATEURS ON _auteur=numUtil 
ORDER BY numPub DESC";
$req = $bd->prepare ($sql); 
$req->execute (); 
$publicationsreq = $req->fetchall ();
$req->closeCursor ();


?>
<!DOCTYPE HTML>
<?php include("structelem/head.php");?>
	<body>
		<?php include('structelem/menu.php')?>
		<main>
		<div class='title'><h2>Fil d'actualité</h2></div>
		<!-- Contenu -->
		<section>
		<div style='text-align:center;margin:5px 0;'><button class='buttonimp' onclick="location.reload();"><i class="fas fa-redo-alt"></i> Actualiser</button></div>
			<?php
			/* Affiche les publications les plus récentes */
			foreach($publicationsreq as $cle => $val){
				
				$date_bdd = "{$val['datePub']}";
				$date_fix = date("d-m-Y", strtotime($date_bdd));
				echo"
				<div class='full_publication'>\n
					<section class='publication_profil'>\n
						<p><a style='text-decoration:none' href='mapage.php?id={$val['numUtil']}'><strong>{$val['prénom']} {$val['nom']}</strong></a></p>\n
						<p><img src='galerie/{$val['img']}'></p>
						{$date_fix} à {$val['heurePub']}\n
					</section>\n
					<section class='publication'>\n
						{$val['textePub']}\n
					</section>
				";
				/* Si l'utilisateur connecté est l'auteur du message -> peut le supprimer */
				if ($numUtil==$val['_auteur']){
				echo"<br><a href='supprpub.php?idpub={$val['numPub']}&utilPage={$val['_utilisateurPage']}&auteur={$val['_auteur']}'><i class='fas fa-trash-alt' style='color:grey;position:absolute;bottom:0;margin-bottom:10px;'></i></a>";
				}
				echo"<span id='repdisp{$val['numPub']}' style='color:rgba(45, 86, 161);position:absolute;bottom:0;margin-bottom:10px;right:120px;'>Afficher les réponses | Répondre  <i class='fas fa-reply'></i></span>\n
				</div>\n";
				// Affichage ou non des réponses en fonction du get (retourné lorsque l'on poste une réponse) */
				if (isset($_GET['disp']) && $val['numPub']==$_GET['disp']){
					echo"<div id='rep_form{$val['numPub']}' style='display:block;margin:0 5px 10px 15%;'>";
				}
				else{echo"<div id='rep_form{$val['numPub']}' style='display:none;margin:0 5px 10px 15%;'>";}	
				// Fomulaire pour répondre à un message */ 
				echo"
					<form method='POST' action='repondre.php'>
						<textarea style='width:95%;' id='text_rep' name='text_rep' placeholder='Réponse' rows=6></textarea>
						<input class='hidden' name='id_pub' value='{$val['numPub']}'>
						<input class='hidden' name='id_page' value='{$val['_utilisateurPage']}'>
						<br><input value='Répondre' class='buttonimp' id='button_rep' name='button_rep' type='submit'>
					</form>
				</div>
				";
				// JavaScript permettant d'afficher ou masquer les réponses en un clic sans recharger la page
				echo"
				<script>
				document.getElementById('repdisp{$val['numPub']}').onclick = function() { 
					var repform_id = document.getElementById('rep_form{$val['numPub']}');
					if (repform_id.style.display === 'none') {
						document.getElementById('rep_form{$val['numPub']}').style.display = 'block';
						document.getElementById('rep_list{$val['numPub']}').style.display = 'block';  
					}
					else {
						document.getElementById('rep_form{$val['numPub']}').style.display = 'none';
						document.getElementById('rep_list{$val['numPub']}').style.display = 'none';  
					}
				} 
				</script>
				";
				// Requête qui récupère les réponses de chaque message
				$sql4 = "SELECT dateRep,heureRep,numRep,textRep,_auteur,_pub,nom,prénom,numUtil FROM REPONSES INNER JOIN UTILISATEURS ON _auteur = numUtil WHERE _pub = '{$val['numPub']}' ORDER BY numRep";
				$req4 = $bd->prepare ($sql4); 
				$req4->execute (); 
				$repTab = $req4->fetchall();
				$req4->closeCursor (); // Requête détruite
				if (isset($_GET['disp']) && $val['numPub']==$_GET['disp']){
					echo"<div id=rep_list{$val['numPub']} style='display:block'>";
				}
				else{echo"<div id=rep_list{$val['numPub']} style='display:none'>";}
				
				foreach($repTab as $num_rep => $rep){
					echo"<div class='full_rep'>
						<section class='rep_profil'>\n
						<p>{$rep['prénom']} {$rep['nom']}</p>\n
						<p>{$rep['dateRep']} | {$rep['heureRep']}</p>\n
						</section>
						<section class='reponse'>\n
							{$rep['textRep']}\n
						</section>
						</div> 
					";
				}
				echo"</div>";
			}
			?>
			</section>
		<!--Fin contenu -->
		<?php include('structelem/footer.php')?>
		</main>	
	</body>
</html>


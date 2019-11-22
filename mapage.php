<?php
session_start();
include("outils/authcheck.php");
include("outils/outils.php");
include("outils/base.php");
$id=$_GET['id'];
$numUtil=$_SESSION['numUtil'];

// Récupère publications avec nom prénom auteur
$sql = "SELECT prénom,nom,_auteur,datePub,heurePub,textePub,numPub,_utilisateurPage,img,numUtil FROM PUBLICATIONS 
INNER JOIN UTILISATEURS ON _auteur=numUtil 
WHERE _utilisateurPage LIKE '{$id}' 
ORDER BY numPub DESC";
$req = $bd->prepare ($sql); 
$req->execute (); 
$publicationsreq = $req->fetchall ();
$req->closeCursor (); // Requête détruite

// Récupère prénom nom propriétaire page
$sql2 = "SELECT numUtil, prénom, nom FROM UTILISATEURS  WHERE numUtil like '{$id}'";
$req2 = $bd->prepare ($sql2); 
$req2->execute (); 
$utilInfo = $req2->fetchall ();
$req2->closeCursor (); // Requête détruite

// Récupère l'image de l'utilisateur connecté
$sql3 = "SELECT img FROM UTILISATEURS  WHERE numUtil like '{$_SESSION['numUtil']}'";
$req3 = $bd->prepare ($sql3); 
$req3->execute (); 
$imgUtil = $req3->fetchall ();
$req3->closeCursor (); // Requête détruite

// Récupère le statut des relations entre l'utilisateur connecté et l'utilisateur "visité
$sqlstatut = "SELECT statut FROM RELATIONS WHERE (_util1={$numUtil} and _util2={$id}) OR (_util2={$numUtil} and _util1={$id})";
$reqstatut = $bd->prepare ($sqlstatut); 
$reqstatut->execute (); 
$listStatut = $reqstatut->fetchall ();
$reqstatut->closeCursor (); 

?>
<!DOCTYPE HTML>
<?php include("structelem/head.php");?>
	<body>
		<?php include('structelem/menu.php')?>
		<main>
		<div class='title'><h2>Page de <?php echo "{$utilInfo[0]['prénom']} {$utilInfo[0]['nom']}"; ?></h2></div>
		<!-- Contenu -->
		
			<?php
		if (!empty($listStatut)){
			if ($listStatut[0]['statut']==2){				//Si ils se sont bloqués
					echo"<h2 style='font-size:35px' class='red'>Bloqué</h2>";
					return;
			}
			elseif($listStatut[0]['statut']==1){			//Si ils sont amis, peut poster sur sa page
				echo"
				<section>
				<div class='publiform'>
					<img style='float:left;width:80px;' src='galerie/{$imgUtil[0]['img']}'>
					<form method='POST' action='publier.php'>
						<textarea class='publiertext' id='text_pub' name='text_pub' placeholder='Qu avez vous à dire' rows=9 cols=80 required></textarea>
						<input class='hidden' name='id_profil' value='{$id}'>
						<br><button style='margin-left:80px' class='buttonimp' id='button_pub' name='button_pub' type='submit'><i class='far fa-paper-plane'></i> Publier</button>
					</form>
				</div>";
			}
			elseif($listStatut[0]['statut']==0){			//Si ils sont en attente d'amitié, ne peut pas encore poster.
				echo"<section><div>Votre demande d'ami est en attente d'acceptation</div>";
			}
			else{echo"<section><div style='font-weight:bold;margin: 5px 10px'>Devenez amis pour pouvoir poster un message !   
				<a class='buttonimp green' href='req_ami.php?id={$id}&s=0'><i class='fas fa-user-plus'></i> Demander en Ami</a></div>
				";}
		}
		else{
			if ($numUtil==$id){								//Si l'utilisateur se trouve sur sa propre page
				echo"
					<section>
					<div class='publiform'>
						<img style='float:left;width:80px;' src='galerie/{$imgUtil[0]['img']}'>
						<form method='POST' action='publier.php'>
							<textarea class='publiertext' id='text_pub' name='text_pub' placeholder='Qu avez vous à dire' rows=9 cols=80 required></textarea>
							<input class='hidden' name='id_profil' value='{$id}'>
							<br><button style='margin-left:80px' class='buttonimp' id='button_pub' name='button_pub' type='submit'><i class='far fa-paper-plane'></i> Publier</button>
						</form>
					</div>";
			}
			else{echo"<section><div style='font-weight:bold;margin: 5px 10px'>Devenez amis pour pouvoir poster un message !   
				<a class='buttonimp green' href='req_ami.php?id={$id}&s=0'><i class='fas fa-user-plus'></i> Demander en Ami</a></div>
				";}	
		}

			/* Code qui affiche en fonction de l'id passé en GET les publications*/
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
				if ($_SESSION['numUtil']==$id || $_SESSION['numUtil']==$val['_auteur']){		//Si l'utilisateur connecté est l'auteur du message, peut le supprimer
				echo"<br><a href='supprpub.php?idpub={$val['numPub']}&utilPage={$val['_utilisateurPage']}&auteur={$val['_auteur']}'><i class='fas fa-trash-alt' style='color:grey;position:absolute;bottom:0;margin-bottom:10px;'></i></a>";
				}
				echo"<span id='repdisp{$val['numPub']}' style='color:rgba(45, 86, 161);position:absolute;bottom:0;margin-bottom:10px;right:120px;'>Afficher les réponses | Répondre  <i class='fas fa-reply'></i></span>\n
				</div>\n";
				// Affichage ou non des réponses en fonction du get (retourné lorsque l'on poste une réponse)
				if (isset($_GET['disp']) && $val['numPub']==$_GET['disp']){
					echo"<div id='rep_form{$val['numPub']}' style='display:block;margin:0 5px 10px 15%;'>";
				}
				else{echo"<div id='rep_form{$val['numPub']}' style='display:none;margin:0 5px 10px 15%;'>";}
				// Fomulaire pour répondre à un message	
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

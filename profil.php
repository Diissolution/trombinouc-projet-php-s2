<?php
session_start();
include("outils/authcheck.php");
include("outils/outils.php");
include("outils/base.php");
$id=$_GET['id'];
$numUtil=$_SESSION['numUtil'];

$sql2 = "SELECT numUtil, prénom, nom, dateAnniv, mail, img FROM UTILISATEURS  WHERE numUtil like '{$id}'";
$req2 = $bd->prepare ($sql2); 
$req2->execute (); 
$utilInfo = $req2->fetchall ();
$req2->closeCursor (); // Requête détruite


$sqlrelation = "SELECT prénom,nom,numUtil FROM RELATIONS,UTILISATEURS where (_util1={$id} AND numUtil = _util2 AND statut=1) OR (_util2={$id} AND numUtil = _util1 AND statut=1)";
$reqrelation = $bd->prepare ($sqlrelation); 
$reqrelation->execute (); 
$listRelation = $reqrelation->fetchall ();
$reqrelation->closeCursor (); // Requête détruite

				
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
		<div class='title'><h2>Profil de <?php echo "{$utilInfo[0]['prénom']} {$utilInfo[0]['nom']}"; ?></h2></div>
        <!-- Contenu -->
        <section class="profil">

        <?php
        $date_bdd = "{$utilInfo[0]['dateAnniv']}";
        $date_fix = date("d M Y", strtotime($date_bdd));
        echo"
        <div class='float'><img width='33%' src='galerie/{$utilInfo[0]['img']}' alt='Image de profil'></div>
        <div class='clear'>
        <i class='far fa-envelope'></i> {$utilInfo[0]['mail']}<br>
        <i class='fas fa-birthday-cake'></i> {$date_fix}<br><br></div>
        <a class='buttonimp' href='mapage.php?id={$utilInfo[0]['numUtil']}'><i class='far fa-file-alt'></i> Page</a>
        ";
        if ($id!=$_SESSION['numUtil']){
			if(!empty($listStatut)){
				if($listStatut[0]['statut']==1){
					echo "<a class='buttonimp red' href='req_ami.php?id={$utilInfo[0]['numUtil']}&s=3'><i class='fas fa-user-times'></i> Ne plus être ami</a></div>\n
					<a class='buttonimp red' href='req_ami.php?id={$utilInfo[0]['numUtil']}&s=2'><i class='fas fa-ban'></i> Bloquer</a></div>\n
					";
				}
				elseif($listStatut[0]['statut']==0){
					echo "<div class='buttonimp yellow'><i class='fas fa-spinner'></i> En attente</a></div></div>\n";
				}
				elseif($listStatut[0]['statut']==2){
					echo"<a class='buttonimp red' href='req_ami.php?id={$utilInfo[0]['numUtil']}&s=3'><i class='fas fa-user-times'></i> Débloquer</a></div>\n";
				}
			}
			else{
				echo"<a class='buttonimp green' href='req_ami.php?id={$id}&s=0'><i class='fas fa-user-plus'></i> Demander en Ami</a></div>
				<a class='buttonimp red' href='req_ami.php?id={$utilInfo[0]['numUtil']}&s=2'><i class='fas fa-ban'></i> Bloquer</a></div>\n";
			}
		}
		
		echo"
		<div class='listamis'>
        <h3> Amis </h3>
		<ul>
		";
		
        foreach($listRelation as $cle => $val){
			echo "<li><a href='profil.php?id={$val['numUtil']}'>{$val['prénom']} {$val['nom']}</a></li>";
		}
		if (empty($listRelation)){
			echo "Aucun :(";
		}

		echo"
		</ul>
		</div>
		";
		
        
		
        if ($id==$_SESSION['numUtil']){
		$listing_rep=listeRep('./galerie');
        $img_extensions=Array('jpg','png','gif','jpeg','bmp','webp');
            
		echo"
		<br>
		<div>
			<h3>Changer ma photo de profil</h3>";
			foreach($listing_rep as $rep){
                $pathinf=pathinfo($rep);
                if (in_array($pathinf['extension'], $img_extensions)){
                   echo "<a href='./changeinfo.php?id_img={$rep}'><img style='margin:5px;' src='./galerie/{$rep}' width='8%'></a>\n";
            	}
			}
		echo"</div>
        <br>
        <div class='changeinfo'>
			<h3> Modifier mes informations </h3>
			<div class='mailform'>
				<form style='width:100' method='POST'  action='./changeinfo.php'>
					<label style='width:auto' for='nouvmail'><i class='far fa-envelope'></i> Nouvelle adresse mail</label>
					<input type='text' name='nouvmail' id='nouvmail' required><br><br>
					<input type='submit' value='Changer le mail' class='buttonimp'><br>
				</form>
			</div>
			";
			if((isset($_GET['err']))&&($_GET['err']='mdp')){ echo"<section class='alertbox'><strong>Mot de passe actuel faux ou les mots de passe ne correspondent pas.</strong></section>\n"; }
			echo"
			<div class='mdpform'>
				<form style='width:100%' method='POST' action='./changeinfo.php'>
					<label style='width:auto' for='mdp_actuel'><i class='fas fa-unlock-alt'></i> Mot de passe actuel</label>
					<input  type='password' name='mdp_actuel' id='mdp_actuel' required>
				<br>
					<label style='width:auto' for='mdp_nouv'><i class='fas fa-lock'></i> Nouveau mot de passe<br></label>
					<input  type='password' name='mdp_nouv' id='mdp_nouv' required>
				<br>
					<label style='width:auto' for='mdp_nouv_conf'><i class='fas fa-lock'></i> Confirmation<br></label>
					<input type='password' name='mdp_nouv_conf' id='mdp_nouv_conf' required>
				<br><br>
					<input type='submit' value='Changer le mot de passe' class='buttonimp'><br>
				</form>
			</div>
        </div>";
		}
        ?>
        <div>
		<br>
        <a href='credits.php'>Crédits images de profil</a>
        </div>
        </section>
		<!--Fin contenu -->
		<?php include('structelem/footer.php')?>
		</main>
	</body>
</html>


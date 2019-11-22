<?php
session_start();
include("outils/authcheck.php");
include("outils/outils.php");
include("outils/base.php");
$numUtil=$_SESSION['numUtil'];



// Requête qui récupère tous les utilisateurs
$sql = "SELECT nom,prénom,numUtil,img FROM UTILISATEURS ORDER BY nom";
$req = $bd->prepare ($sql); 
$req->execute (); 
$listUtil = $req->fetchall ();
$req->closeCursor ();


?>

<!DOCTYPE HTML>
<?php include("structelem/head.php");?>
	<body>
		<?php include('structelem/menu.php')?>
		<main>
		<div class='title'><h2>Annuaire</h2></div>
        <section class="annuaire">
        <!------- Recherche d'utilisateur ------->
        <form class='searchbar' method='GET' action='annuaire.php'><i class="fas fa-search"></i>
            <input type='text'name='recherche' placeholder='Rechercher...'/><?php if (isset($_GET['recherche'])){ echo "<a href='annuaire.php'><i class='fas fa-eraser' style='color:rgb(16, 57, 134);'></i></a>";}?>
            <input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
        </form><br> 
            <?php
            /* PHP pour la recherche d'utilisateur */
            if (isset($_GET['recherche'])){
                $rech=strip_tags($_GET['recherche']);
                $sql = "SELECT nom,prénom,numUtil,img FROM UTILISATEURS WHERE nom LIKE '%{$rech}%' OR prénom LIKE '%{$rech}%' ORDER BY nom";
                $req = $bd->prepare ($sql); 
                $req->execute ();
                $listUtil = $req->fetchall ();
                $req->closeCursor ();
               if (empty($listUtil) ==  TRUE) {
                echo "<section class='alertbox' style='width:33%;text-align:center;'>Aucun résultat</section>";
               }
            }
            /* Requête qui récupère le statut de la relation entre l'utilisateur connecté et l'utilisateur affiché dans l'annuaire */
            foreach($listUtil as $cle => $val){
				$sqlrelation = "SELECT statut FROM RELATIONS where (_util1={$numUtil} and _util2={$listUtil[$cle]['numUtil']}) OR (_util2={$numUtil} and _util1={$listUtil[$cle]['numUtil']})";
				$reqrelation = $bd->prepare ($sqlrelation); 
				$reqrelation->execute (); 
				$listRelation = $reqrelation->fetchall ();
				$reqrelation->closeCursor (); 
				
				if ($val['numUtil']!=$numUtil){
                echo"
                <div class='profilcard float30'>
                    <div class='nom'>{$listUtil[$cle]['prénom']} {$listUtil[$cle]['nom']}</div>
                    <img style='width:40%;margin:10px 5px;' src='galerie/{$listUtil[$cle]['img']}' alt='Photo de profil'>
                    <div><a class='buttonimpyellow' href='profil.php?id={$listUtil[$cle]['numUtil']}'><i class='far fa-user-circle'></i> Profil</a>
                    <a class='buttonimp' href='mapage.php?id={$listUtil[$cle]['numUtil']}'><i class='far fa-file-alt'></i> Page</a>
                    ";
                    /* Affiche un bouton en fonction du statut (demande d'ami etc) */
                    if (empty($listRelation)) {
						echo"<a class='buttonimp green' href='req_ami.php?id={$listUtil[$cle]['numUtil']}&s=0'><i class='fas fa-user-plus'></i> Demander en Ami</a></div>";
					}
					elseif($listRelation[0]['statut']==0){
						echo"<div class='buttonimp yellow'><i class='fas fa-spinner'></i> En attente</a></div></div>";
					}
					elseif($listRelation[0]['statut']==1){
						echo"<div class='buttonimp stronggreen'><i class='fas fa-user-check'></i> Ami</div></div>";
					}
					elseif($listRelation[0]['statut']==2){
						echo"<div class='buttonimp red'><i class='fas fa-ban'></i> Bloqué</div></div>";
					}
					else{echo"</div>";}
                    
                echo"
                </div>
                ";
				}
            }
            ?>
        </section>
		<!--Fin contenu -->
		<?php include('structelem/footer.php')?>
		</main>	
	</body>
</html>

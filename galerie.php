<?php
session_start();
include("outils/authcheck.php");
include("outils/base.php");
include("outils/outils.php");
?>
<!DOCTYPE HTML>
<?php include("structelem/head.php");?>
	<body>
		<?php include('structelem/menu.php')?>
        <main>
		<div class='title'><h2>Galerie</h2></div>
		<!-- Contenu -->
		<section>

            <?php
            $galerie_rep='./galerie';
            $listing_rep=listeRep($galerie_rep);
            $img_extensions=Array('jpg','png','gif','jpeg','bmp','webp');   //Liste des extensions acceptées comme "image"
                foreach($listing_rep as $rep){      //Pour chaque fichier trouvé dans /galerie
                    $pathinf=pathinfo($rep);        
                    if (in_array($pathinf['extension'], $img_extensions)){  //Si l'extension du fichier se trouve dans le tableau situé plus haut, affiche le fichier
                        echo "<img style='margin:15px 10px;' src='./galerie/{$rep}' width='15%' height='25%'>\n";
                    }
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

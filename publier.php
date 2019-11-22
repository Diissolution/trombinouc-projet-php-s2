<?php
session_start();
include("outils/outils.php");
include("outils/base.php");
/* Récupération de la date et l'heure */
$timestamp = time ();
$dateduj = date("Y-m-d",$timestamp);
$heure= date("G:i:s",$timestamp);

/* Récupération des valeurs du formulaire de publication */
$id_profil=$_POST['id_profil'];
$id_auteur=$_SESSION['numUtil'];
$text_pub=str_replace("'", "''", strip_tags($_POST['text_pub']));

/* Requete pour publier sur la page (en fonction de l'id passé en POST!)*/

$sqlpublier = "INSERT INTO PUBLICATIONS (_auteur,_utilisateurPage,datePub,heurePub,textePub) VALUES ({$id_auteur},:idprofil,'{$dateduj}','{$heure}',:textpub)";
//debug($sqlpublier);
$marqueurs=array('idprofil'=>$id_profil,'textpub'=>$text_pub);
//debug($marqueurs);
$reqpublier = $bd->prepare ($sqlpublier); 
$reqpublier->execute ($marqueurs);
$reqpublier->closeCursor (); // Requête détruite

header("Location:mapage.php?id={$id_profil}");
exit();
?>

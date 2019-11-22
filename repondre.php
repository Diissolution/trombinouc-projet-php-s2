<?php
session_start();
include("outils/outils.php");
include("outils/base.php");
/* Récupération de la date et l'heure */
$timestamp = time ();
$dateduj = date("Y-m-d",$timestamp);
$heure= date("G:i:s",$timestamp);

/* Récupération des valeurs pour la réponse */
$id_pub=$_POST['id_pub'];
$id_page=$_POST['id_page'];
$id_auteur=$_SESSION['numUtil'];
$text_rep= str_replace("'", "''", strip_tags($_POST['text_rep']));

/* Requete pour publier sur la page (en fonction de l'id passé en POST!)*/

$sqlrep = "INSERT INTO REPONSES (_auteur,_pub,dateRep,heureRep,textRep) VALUES ({$id_auteur},:id_pub,'{$dateduj}','{$heure}',:text_rep)";
$marqueurs=array('id_pub'=>$id_pub,'text_rep'=>$text_rep);
$reqrep = $bd->prepare ($sqlrep); 
$reqrep->execute ($marqueurs);
$reqrep->closeCursor (); // Requête détruite

header("Location:mapage.php?id={$id_page}&disp={$id_pub}");
exit();
?>

<?php
session_start();
include("outils/outils.php");
include("outils/base.php");

$numPub=$_GET['idpub'];
$utilPage=$_GET['utilPage'];
$auteur=$_GET['auteur'];

if(($utilPage==$_SESSION['numUtil']) || ($auteur==$_SESSION['numUtil'])){       //Vérifie si l'utilisateur qui supprime est bien l'auteur du message ou supprime un message de sa page
$sqlsuppr = "DELETE FROM PUBLICATIONS WHERE numPub = {$numPub}";
$reqsuppr = $bd->prepare ($sqlsuppr); 
$reqsuppr->execute ();
$reqsuppr->closeCursor (); // Requête détruite
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>

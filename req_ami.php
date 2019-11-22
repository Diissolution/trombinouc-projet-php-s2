<?php

session_start();
include("outils/authcheck.php");
include("outils/outils.php");
include("outils/base.php");

$id_ami=$_GET['id'];
$statut=$_GET['s'];

if ($statut==2){
// Requête qui supprime les relations précédentes
$sqlsuppr = "DELETE FROM RELATIONS WHERE (_util1=:id_ami AND _util2={$_SESSION['numUtil']}) OR (_util2=:id_ami AND _util1={$_SESSION['numUtil']})";
$marqueurs=array('id_ami'=>$id_ami);
$reqsuppr = $bd->prepare ($sqlsuppr); 
$reqsuppr->execute ($marqueurs);
$reqsuppr->closeCursor (); 
//Requête qui bloque l'utilisateur par la suite
$sqlbloq = "INSERT INTO RELATIONS (_util1,_util2,statut) VALUES ({$_SESSION['numUtil']},:id_ami,:statut)";
$marqueurs=array('id_ami'=>$id_ami,'statut'=>$statut);
$reqbloq = $bd->prepare ($sqlbloq); 
$reqbloq->execute ($marqueurs);
$reqbloq->closeCursor (); 
}
elseif ($statut==0){
//Requête de demande en ami
$sqlami = "INSERT INTO RELATIONS (_util1,_util2,statut) VALUES ({$_SESSION['numUtil']},:id_ami,:statut)";
$marqueurs=array('id_ami'=>$id_ami,'statut'=>$statut);
$reqami = $bd->prepare ($sqlami); 
$reqami->execute ($marqueurs);
$reqami->closeCursor ();
}
elseif ($statut==1){
//Requête qui confirme la demande d'ami
$sqlconf = "UPDATE RELATIONS SET statut	= :statut WHERE _util1=:id_ami AND _util2={$_SESSION['numUtil']}";
$marqueurs=array('id_ami'=>$id_ami,'statut'=>$statut);
$reqconf = $bd->prepare ($sqlconf); 
$reqconf->execute ($marqueurs);
$reqconf->closeCursor ();
}
elseif ($statut==3){
//Requête refus demande d'ami ou suppression ami
$sqlsuppr = "DELETE FROM RELATIONS WHERE (_util1=:id_ami AND _util2={$_SESSION['numUtil']}) OR (_util2=:id_ami AND _util1={$_SESSION['numUtil']}) ";
$marqueurs=array('id_ami'=>$id_ami);
$reqsuppr = $bd->prepare ($sqlsuppr); 
$reqsuppr->execute ($marqueurs);
$reqsuppr->closeCursor ();

}
	
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>

<?php
session_start();
include("outils/authcheck.php");
include("outils/outils.php");
include("outils/base.php");

/* Page PHP permettant de changer : l'image de profil, le mail et le mot de passe */

/*------ IMAGE ------*/
if (isset($_GET['id_img'])){
	$_SESSION['img']=$_GET['id_img'];
	$sqlch_img = "UPDATE UTILISATEURS SET img = :nouvimg WHERE numUtil = {$_SESSION['numUtil']}";
	$marq_ch_img=array('nouvimg'=>$_GET['id_img']);
	$reqch_img = $bd->prepare ($sqlch_img); 
	$reqch_img->execute ($marq_ch_img);
	$reqch_img->closeCursor ();
	header("Location:profil.php?id={$_SESSION['numUtil']}");
	exit();
}

/*------ MAIL ------*/
if (isset($_POST['nouvmail'])){
			$_SESSION['mail']=$_POST['nouvmail'];
			$sqlch_mail = "UPDATE UTILISATEURS SET mail = :nouvmail 
						WHERE numUtil = {$_SESSION['numUtil']}";
			$marq_ch_mail=array('nouvmail'=>$_POST['nouvmail']);
			$reqch_mail = $bd->prepare ($sqlch_mail); 
			$reqch_mail->execute ($marq_ch_mail);
			$reqch_mail->closeCursor ();
			header("Location:profil.php?id={$_SESSION['numUtil']}");
			exit();
}

/*------ MOT DE PASSE ------*/
elseif (isset($_POST['mdp_actuel'])){
	
	$mdp_actuel=$_POST['mdp_actuel'];
	$mdp_nouv=$_POST['mdp_nouv'];
	$mdp_nouv_conf=$_POST['mdp_nouv_conf'];

	$sqlverif_mdp = "SELECT motdepasse FROM UTILISATEURS WHERE numUtil = {$_SESSION['numUtil']}";
	$reqverif_mdp = $bd->prepare ($sqlverif_mdp); 
	$reqverif_mdp->execute ();
	$verif_mdp = $reqverif_mdp->fetchall ();
	$reqverif_mdp->closeCursor ();

	if(($verif_mdp[0]['motdepasse']==$mdp_actuel)&&($mdp_nouv==$mdp_nouv_conf)){
			$sqlch_mdp = "UPDATE UTILISATEURS SET motdepasse = :nouvmdp
						WHERE numUtil = {$_SESSION['numUtil']}";
			$marq_ch_mdp=array('nouvmdp'=>$mdp_nouv);
			$reqch_mdp = $bd->prepare ($sqlch_mdp); 
			$reqch_mdp->execute ($marq_ch_mdp);
			$reqch_mdp->closeCursor ();
			header("Location:profil.php?id={$_SESSION['numUtil']}");
			exit();
		}
	else{header("Location:profil.php?id={$_SESSION['numUtil']}&err=mdp");exit();}
}


?>

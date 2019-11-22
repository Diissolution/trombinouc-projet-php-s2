<?php
session_start();
include("outils/base.php");
include("outils/outils.php");

foreach($_POST as $cle => $val){
    $signupArray[$cle]=strip_tags($val);
}


//Vérifie si l'utilisateur n'a pas déjà un compte avec le mail proposé
$sql = "SELECT mail FROM UTILISATEURS WHERE mail = '{$signupArray['mail_insc']}'";
$req = $bd->prepare ($sql); 
$req->execute (); 
$mail_existant = $req->fetchall ();
$req->closeCursor ();



if ($signupArray['passwd_insc']==$signupArray['passwd_conf']){
    if(empty($mail_existant)==TRUE){
        $sql2 = "INSERT INTO UTILISATEURS (prénom, nom, dateAnniv, mail, motdepasse, img)
        VALUES (:prenom, :nom, :dateAnniv, :mail_insc, :passwd_insc, 'profil_base.png')";
        $marqueurs=array('prenom'=>$signupArray['prenom'],'nom'=>$signupArray['nom'],'dateAnniv'=>$signupArray['dateAnniv'],'mail_insc'=>$signupArray['mail_insc'],'passwd_insc'=>$signupArray['passwd_insc']);
        $req2 = $bd->prepare ($sql2); 
        $req2->execute ($marqueurs); 
        $req2->closeCursor (); // Requête détruite
    }
    else{
        header('Location:index.php?msg=mail');
        exit();
    }
}
else {
    header('Location:index.php?msg=passwd');
    exit();
}

header('Location:index.php?msg=insc_success');
exit();
?>

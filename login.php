<?php
session_start();
include("outils/base.php");
include("outils/outils.php");
$mail=strip_tags($_POST['mail']);
$passwd=$_POST['passwd'];

// Requête qui récupère l'info de l'utilisateur qui essaye de se connecter
$sql="SELECT * FROM UTILISATEURS WHERE mail=:mailpost";
$req = $bd->prepare ($sql);
$marqueurs=array('mailpost'=>$mail);
$req->execute ($marqueurs);
$result = $req->fetchall ();
$req->closeCursor ();

if (isset($passwd) && !empty($result)){ 
    if($result[0]['motdepasse']==$passwd){ //Comparaison mot de passe récupéré dans BDD et mot de passe du formulaire
    $_SESSION['mail']=$mail;                // Si juste, variables de session initialisées
    $_SESSION['prénom']=$result[0]['prénom'];
    $_SESSION['nom']=$result[0]['nom'];
    $_SESSION['dateAnniv']=strval($result[0]['dateAnniv']);
    $_SESSION['numUtil']=$result[0]['numUtil'];
    $_SESSION['img']=$result[0]['img'];
    $_SESSION["auth"]=TRUE;
    header("Location:mapage.php?id={$_SESSION['numUtil']}");
    exit();
    }
}
else{
    header('Location:index.php?msg=err');
    exit();
}
?>

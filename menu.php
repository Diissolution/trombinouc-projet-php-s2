<?php
echo"
<nav id='sidenav' class='sidenav'>\n";
	echo"<a id='trombinouclogo' class='titre' href='/~ra803006/ext/trombinouc/index.php'>Trombinouc</a>";
	
	//Menu utilisateur si connecté
    if (isset($_SESSION['auth']) || ($_SESSION['auth']==TRUE)){
    echo"
    <div class='logmenu'><a href='/~ra803006/ext/trombinouc/profil.php?id={$_SESSION['numUtil']}'><i class='far fa-user-circle'></i> {$_SESSION['prénom']} {$_SESSION['nom']}</a>\n
    <img style='margin-top:5px;' src='/~ra803006/ext/trombinouc/galerie/{$_SESSION['img']}' width=50% >\n<br>
    <span style='color:LawnGreen'>Connecté</span>\n</div>";
    }
    if (isset($_SESSION['auth']) || ($_SESSION['auth']==TRUE)){
	$sqlmenu = "SELECT _util1,_util2,prénom,nom FROM RELATIONS INNER JOIN UTILISATEURS ON _util1=numUtil where (_util2={$_SESSION['numUtil']} AND statut=0)";
	$reqmenu = $bd->prepare ($sqlmenu); 
	$reqmenu->execute (); 
	$listMenu = $reqmenu->fetchall ();
	$reqmenu->closeCursor ();
    echo"
    <a href='/~ra803006/ext/trombinouc/mapage.php?id={$_SESSION['numUtil']}'><i class='far fa-file-alt'></i> Ma Page</a>
    <a href='/~ra803006/ext/trombinouc/fil_actu.php'><i class='far fa-newspaper'></i> Fil d'actu</a>
    <a href='/~ra803006/ext/trombinouc/annuaire.php'><i class='fas fa-user-friends'></i> Annuaire</a>
    <a href='/~ra803006/ext/trombinouc/galerie.php'><i class='far fa-images'></i> Galerie</a>
    <a href='/~ra803006/ext/trombinouc/logout.php'><i class='fas fa-sign-out-alt'></i> Déconnexion</a><br>
    
    <div class='ami_req'>
    ";
    
    //Menu des demandes d'ami
    foreach($listMenu as $cle=>$val){
		echo"<div class='flash'>
		{$val['prénom']} {$val['nom']}\n
		<br> Vous demande en ami\n</div>
		<a class='buttonimp green' href='req_ami.php?id={$val['_util1']}&s=1'>Accepter</a>\n
		<a class='buttonimp red' href='req_ami.php?id={$val['_util1']}&s=3'>Refuser</a>\n
		";
	}
	echo"</div>";
    }
    else{echo"<div class='nologmenu'>C'est un peu vide par ici... Inscris-toi pour découvrir les fonctionnalités !</div>";}
	echo"</nav>";
?>

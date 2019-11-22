<?php

function debug ($var){
	echo"<pre>\n";
	print_r($var);
	echo"</pre>\n";
}

function listeRep($unRep) {
	    $allFic=array();
	    if (is_dir($unRep) == FALSE) {
	        echo "{$unRep} n'est pas un répertoire !";
	    }
	    else {
	    	$rep = opendir($unRep);
	    	if ($rep == FALSE) {
	    	    echo "Impossible d'ouvrir le répertoire {$unRep}";
	    	}
	    	else {
	    	    while (($fic = readdir($rep)) == TRUE) {
	    	        $allFic[]=$fic;
	    	    }
	    	    closedir($rep);
	    	    sort($allFic);
	    	}
	    }
	    return $allFic;
}

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('html_errors', 0);
*/
?>

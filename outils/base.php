<?php
try {
    $bd = new PDO ( "mysql:host=localhost;dbname=ra803006",
    "ra803006", "ra80300614e0");
    $bd->exec ('SET NAMES utf8') ;
    }
    catch (Exception $e) {
    die ("Erreur: Connexion à la base impossible");
    } 
?>
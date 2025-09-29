<?php
try{
    $servername = '6v8xp.myd.infomaniak.com';
    $username = "6v8xp_Esystem";
    $password = "Esti@system64!";
    
    //base en ligne
    $baseD =new PDO("mysql:host=6v8xp.myd.infomaniak.com;dbname=6v8xp_estiasystem;charset=utf8", $username,$password);

    //base local
  //$baseD =new PDO("mysql:host=localhost;dbname=estiasystem;charset=utf8", 'root','');
}
catch (Exception $e){
//affiche l'erreur La fonction getMessage permet de récupérer les messages d'erreurs 
die('<span style="color: red;">Une erreur est survenue dans la connection de la base de donnée: '.$e->getMessage());
}
// si pas d'erreur le programme continue 
//echo '<span style="color: green;">Connexion Etabli !</span>';

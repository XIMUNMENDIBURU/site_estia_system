<?php
session_start();
if (isset($_GET['id']) && $_SESSION['LOGGED_USER']) {
    // Récupérez l'ID de l'élément depuis le paramètre GET
    $idElement = $_GET['id']; // Assurez-vous de valider et de sécuriser cette valeur
    include('config.php');


    $stmt = $baseD->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$idElement]);

    echo "L'élément a été supprimé avec succès.";
}
header("Location: evenements.php");
exit();

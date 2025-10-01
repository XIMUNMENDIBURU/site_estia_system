<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Durée maximale de la session en secondes (30 minutes)
$maxSessionDuration = 1800;

// Vérifiez si la session a expiré
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $maxSessionDuration)) {
    session_unset();
    session_destroy();
    header("Location: adminES.php");
    exit();
}

// Mettez à jour le temps d'activité de la session
$_SESSION['last_activity'] = time();

if(!isset($_SESSION['LOGGED_USER'])){
    $_SESSION['LOGGED_USER'] = 0;
}

// Fonction pour déterminer la classe active du menu
function getActiveClass($currentPage) {
    $currentFile = basename($_SERVER['PHP_SELF']);
    return ($currentFile === $currentPage) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ESTIA SYSTEM est une association étudiante de l'ESTIA, proposant des projets et des évènements dans le domaine de l'électronique, la mécanique et l'informatique">
    <meta name="keywords" content="Robotique, ESTIA, ESTIA SYSTEM, Mécatronique, Coupe de France de Robotique, CFR, Electronique, Informatique, Mécanique, Robot, Association">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <?php if(isset($additionalCSS)): ?>
        <link rel="stylesheet" href="assets/css/<?php echo $additionalCSS; ?>.css">
    <?php endif; ?>
    <link rel="shortcut icon" href="../images/Logo_Estia_System/logoblanc.ico">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ESTIA SYSTEM' : 'ESTIA SYSTEM'; ?></title>
</head>
<body class="<?php echo isset($bodyClass) ? $bodyClass : 'default-page'; ?>">
    <header>
        <nav class="nav">
            <input type="checkbox" id="nav-checkbox" class="nav-checkbox">
            <label for="nav-checkbox" class="toggle">
                <img class="menu" src="../images/navigation-button.png" alt="Menu">
            </label>
            <ul class="menu">
                <li><a href="../pages/accueil.php"><img class="logo" src="../images/Logo_Estia_System/logoblanc.png" alt="logo asso"></a></li>
                <li><a href="../pages/apropos.php" class="<?php echo getActiveClass('apropos.php'); ?>">A Propos</a></li>
                <li><a href="../pages/project.php" class="<?php echo getActiveClass('project.php'); ?>">Projets</a></li>
                <li><a href="../pages/evenements.php" class="<?php echo getActiveClass('evenements.php'); ?>">Évènements</a></li>
                <li><a href="../pages/contact.php" class="<?php echo getActiveClass('contact.php'); ?>">Contact</a></li>
                <?php if($_SESSION['LOGGED_USER']): ?>
                    <li><a href="adminES.php" class="<?php echo getActiveClass('adminES.php'); ?>">Administration</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>

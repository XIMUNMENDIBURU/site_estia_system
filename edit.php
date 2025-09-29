<?php 
    session_start();
    // Durée maximale de la session en secondes (30 minutes ici)
    $maxSessionDuration = 1800;

    // Vérifiez si la session a expiré
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $maxSessionDuration)) {
        // La session a expiré, déconnectez l'utilisateur
        session_unset();
        session_destroy();
        header("Location: admin.php"); // Redirigez vers la page de connexion
        exit();
    }

    // Mettez à jour le temps d'activité de la session
    $_SESSION['last_activity'] = time();

    if(!isset($_SESSION['LOGGED_USER'])){
        $_SESSION['LOGGED_USER']=0;
    }
    
?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"content="ESTIA SYSTEM est une association étudiante de l'ESTIA, proposant des projets et des évènements dans le domaine de l'électronique, la mécanique et l'informatique">
    <meta name="keywords" content="Robotique, ESTIA, ESTIA SYSTEM, Mécatronique, Coupe de France de Robotique, CFR, Electronique, Informatique, Mécanique, Robot, Association">
    <link rel="stylesheet" href="mise_en_pages.css">
    <link rel="stylesheet" href="evenements.css">
    <link rel="shortcut icon" href="images/Logo_Estia_System/logoblanc.ico">
    <title>ESTIA SYSTEM</title>

</head>
<body class="accueil__page2" id="admin">
    <main>
        <h1>Administration du site</h1>
        <div id="liens">
            <?php if($_SESSION['LOGGED_USER']) : ?>
                <div id="deconnexion">
                    <a class="boutonsAdmin" id="boutonDeco" href="logout.php">Déconnexion</a>
                </div>
                <?php endif ?>
            <div id="retour">
                <a class="boutonsAdmin" id="boutonRetour" href="evenements.php">Retour</a>
            </div>
        </div>
        <div id="formulaire">
            <?php
                include_once("login.php");
            ?>
        </div>
        <div if="formulaire">
            <?php if($_SESSION['LOGGED_USER']) : ?>
                <?php if(isset($_SESSION['IMAGE_UPLOAD'])) : ?>
                    <label class="texteR">L'image n'a pas pu être ajoutée</label>
                <?php endif ?>
                <?php if(isset($_SESSION['EVENT_UPLOAD'])) : ?>
                    <?php if($_SESSION['EVENT_UPLOAD']==1) : ?>
                        <label class="texteV">Le formulaire a été ajouté</label>
                    <?php endif ?>
                    <?php if($_SESSION['EVENT_UPLOAD']==0) : ?>
                        <label class="texteR">Le formulaire n'a pas pu être ajouté</label>
                    <?php endif ?>
                <?php endif ?>

                <?php 
                    $idCard = $_GET['id'];
                    include_once("config.php");
                    $request = "SELECT * FROM events WHERE id=".$idCard;

                    $statement = $baseD->prepare($request);
                    if($statement->execute()){
                        $result =$statement->fetch();
                        $epoch = $result['date'];
                        $dt = new DateTime("@$epoch");
                        $imagePath = "images/evenements/" . $result['image'];
                    ?>
                
                <form id="form-event" action="upload.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_card" value="<?php echo $result['id']; ?>">
                    <p>
                        <label for="titre">Titre :</label>
                        <input type="text" name="titre" id="titre" value="<?php echo $result['titre']; ?>" required>
                    </p>
                    <p>
                        <label for="date">Date :</label>
                        <input type="date" name="date" id="date" value="<?php echo $dt->format('Y-m-d'); ?>" required>
                    </p>
                    <p id="pParagraphe">
                        <label id="paragraphe" for="paragraphe">Texte :</label>
                        <textarea name="paragraphe" id="paragraphe"><?php echo $result['texte']; ?></textarea>
                    </p>
                    <p id="pImage">
                        <label for="image">Image à télécharger (2Mo max):</label>
                        <input type="file" name="image" id="image" accept="image/*" onChange="previewImage(this);">
                        <img id="preview" src="<?php echo $imagePath; ?>" alt="" style="display:block; width:200px;">
                    </p>
                    <p>
                        <input id="bouton" value="Modifier" name="form_edit" type="submit">
                    </p>
                </form>
                <?php } ?>
            <?php endif ?>
        </div>
    </main>
    <script>
        function previewImage(input) {
        var preview = document.getElementById('preview');
        var file = input.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(file);
    }
</script>
</body>
</html>
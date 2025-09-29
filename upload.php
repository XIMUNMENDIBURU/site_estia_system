<?php
    session_start();

    // Inclure la configuration de la base de données
    include('config.php');

    // Vérifier si le formulaire a été soumis

    function imageLoad(){
        $targetDirectory = "images/evenements/";
        $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        echo $_FILES["image"]["size"];
        // Vérifiez la taille du fichier
        if ($_FILES["image"]["size"] > 2100000) {
            echo "Désolé, votre fichier est trop volumineux.";
            $uploadOk = 0;
        }
        else{
            // Vérifiez si le fichier est une image réelle
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                echo "Le fichier est une image - " . $check["mime"] . ".";
                $uploadOk = 1;

                // Autorisez certains formats de fichiers
                $allowedFormats = array("jpg", "jpeg", "png", "gif");
                if (!in_array($imageFileType, $allowedFormats)) {
                    echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                    $uploadOk = 0;
        }
            } else {
                echo "Le fichier n'est pas une image.";
                $uploadOk = 0;
            }
        }

        

        // Vérifiez si $uploadOk est défini à 0 par une erreur
        if ($uploadOk == 0) {
            echo "Désolé, votre fichier n'a pas été téléchargé.";
            $_SESSION['IMAGE_UPLOAD']=1;
        } else {
            // Téléchargez le fichier dans le répertoire spécifié
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                echo "Le fichier " . basename($_FILES["image"]["name"]) . " a été téléchargé.";
            } else {
                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
                $_SESSION['IMAGE_UPLOAD']=1;
            }
        }
    }


    if(isset($_POST['form_submit'])) {
        // Récupérer les données du formulaire
        $titre = $_POST['titre'];
        $date = new DateTime($_POST['date']);
        $timestamp = $date->getTimestamp();
        $paragraphe = $_POST['paragraphe'];
        imageLoad();
        // if(isset($_FILES['photo']['tmp_name'])){
            
        // }
        // else{
        //     $_SESSION['IMAGE_UPLOAD']=1;
        // }
            // Gérer le téléchargement de l'image
            $image = $_FILES['image']['name'];

            // Préparer la requête SQL pour insérer les données dans la base de données
            $sql = "INSERT INTO events (titre, date, texte, image) VALUES (:titre, :date, :texte, :image)";
            $statement = $baseD->prepare($sql);
            // Lier les valeurs aux paramètres de la requête
            $statement->bindValue(':titre', $titre, PDO::PARAM_STR);
            $statement->bindValue(':date', $timestamp, PDO::PARAM_INT);
            $statement->bindValue(':texte', $paragraphe, PDO::PARAM_STR);
            $statement->bindValue(':image', $image, PDO::PARAM_STR);

            // Exécuter la requête SQL
            if ($statement->execute()) {
                echo "Enregistrement réussi.";
                $_SESSION['EVENT_UPLOAD']=1;
            } else {
                echo "Erreur lors de l'enregistrement : " . $statement->errorInfo()[2];
                $_SESSION['EVENT_UPLOAD']=0;
            }

        header("Location: adminES.php");
        exit();
    }
    if(isset($_POST['form_edit'])) {
        // Récupérer les données du formulaire
        $idCard = $_POST["id_card"];
        $titre = $_POST['titre'];
        $date = new DateTime($_POST['date']);
        $timestamp = $date->getTimestamp();
        $paragraphe = $_POST['paragraphe'];

        if($_FILES['image']['name']!=null){
            imageLoad();
            // Gérer le téléchargement de l'image
            $image = $_FILES['image']['name'];

            // Préparer la requête SQL pour insérer les données dans la base de données
            $sql = "UPDATE events SET titre = ?, date = ?, texte = ?, image = ? WHERE id = ?";

            $statement = $baseD->prepare($sql);
            // Lier les valeurs aux paramètres de la requête
            $statement->execute([$titre,$timestamp,$paragraphe,$image, $idCard]);
        }
        else{
            // Préparer la requête SQL pour insérer les données dans la base de données
            $sql = "UPDATE events SET titre = ?, date = ?, texte = ? WHERE id = ?";
            $statement = $baseD->prepare($sql);
            // Lier les valeurs aux paramètres de la requête
            $statement->execute([$titre,$timestamp,$paragraphe, $idCard]);
        }
        
        

        header("Location: evenements.php");
        exit();
    }
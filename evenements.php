<?php 
    session_start();
    // Durée maximale de la session en secondes (30 minutes ici)
    $maxSessionDuration = 1800;

    // Vérifiez si la session a expiré
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $maxSessionDuration)) {
        // La session a expiré, déconnectez l'utilisateur
        session_unset();
        session_destroy();
        header("Location: adminES.php"); // Redirigez vers la page de connexion
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
    <meta name="description" content="ESTIA SYSTEM est une association étudiante de l'ESTIA, proposant des projets et des évènements dans le domaine de l'électronique, la mécanique et l'informatique">
    <meta name="keywords" content="Robotique, ESTIA, ESTIA SYSTEM, Mécatronique, Coupe de France de Robotique, CFR, Electronique, Informatique, Mécanique, Robot, Association">
    <link rel="stylesheet" href="mise_en_pages.css">
    <link rel="stylesheet" href="evenements.css">
    <link rel="shortcut icon" href="images/Logo_Estia_System/logoblanc.ico">
    <title>ESTIA SYSTEM</title>

</head>

<body class="accueil__page2">
    <header>
        <nav class="nav">
            <input type="checkbox" id="nav-checkbox" class="nav-checkbox">
            <label for="nav-checkbox" class="toggle">
                <img class="menu" src="images/navigation-button.png" alt>
            </label>
            <ul class="menu">
                <li><a href="accueil.html"><img class="logo" src="images/Logo_Estia_System/logoblanc.png" alt="logo asso"></a></li>
                <li><a href="apropos.html">A Propos</a></li>
                <li><a href="project.html">Projets</a></li>
                <li><a href="evenements.php">Évènements</a></li>
                <li><a href="contact.html">Contact</a></li>
                <?php if($_SESSION['LOGGED_USER']) : ?>
                    <li><a href="adminES.php">Administration</a></li>
                <?php endif ?>
            </ul>
        </nav>

    </header>
    <main>
        <div class="apropos_asso">
            <h2>Nos différents évènements</h2>
            <p class="presentation">ESTIA SYSTEM organise différents évènements au cours de l'année comme des Afterworks et 
                la raclette des adhérents. Nous participons également à des évènements de l'ESTIA comme les portes ouvertes 
                ou le StartUp4Teens, organisé par la French Tech Pays Basque, qui nous permettent de mettre en lumière notre 
                association. De plus nous participons à la coupe de France de Robotique chaque année et aide ses adhérents 
                qui on en besoin sur leurs projets tech ainsi qu'a travers différent tutoriel.
            </p>
        </div>
        <?php
            include_once("config.php");
            $request = "SELECT * FROM events ORDER BY date DESC";

            $statement = $baseD->prepare($request);

            if($statement->execute()){
                $results =$statement->fetchAll();
                echo "<div id='listeEvents'>";
                $dateAvant=9999;
                foreach($results as $index => $result){
                    $checkboxId = "checkboxId" . $index;
                    $dateId = "dateId" . $index;
                    $titreId = "titreId" . $index;
                    $epoch = $result['date'];
                    $dt = new DateTime("@$epoch");
                    $imagePath = "images/evenements/" . $result['image'];
                    if($dt->format('Y')<$dateAvant){
                        $dateAvant = $dt->format('Y');
                        echo "<p class='newDate'>" . $dateAvant . "</p>";   
                    }

                    if($index%2==0){
                        echo "<div class='listeEvent gauche'>";
                    }
                    else{
                        echo "<div class='listeEvent droite'>";
                    }
                    echo "<input type='checkbox' class='boutonList' id='$checkboxId'>";
                    echo "<div class='date dateCache' id='$dateId'>".$dt->format('d-m-Y')."</div>";
                    echo "<label for='$checkboxId' class='boutonToggle'>";
                    echo "<label for='$checkboxId' id='$titreId' class='titreEvent'>".$result['titre']."</label></label>";
                    echo "<hr><div class='texteCache'>";
                    echo "<div class='date'>".$dt->format('d-m-Y')."</div>";
                    echo "<p class='paragraphe' style='white-space: pre-line'>".$result['texte']."</p>";
                    ?>
                    <img class='imageEvent' src="<?php echo $imagePath ?>" alt>
                    
                    <?php if($_SESSION['LOGGED_USER']) : ?>
                        <div class='boutonBas'><div class='edit'><button onclick="editElement(<?php echo $result['id']; ?>)"><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAABkUlEQVRIS+2X21HDMBBFV8AnMC7BdEAJpAQ6gA5wBdBBUgIdQAekA9IB6QAPjy8e4i5jk7WslWVbHv+gv0SPs/euNtoYmmmYmbg0KfjQ0tmbobVPnBecWco+iHJMZjGOHBBtSkOlXMtQ7L/Dd5tXQwv3nBb42NKVJbrGwigo1q6gqlCg9RlrF94As9IvoqcxUA7gyP6ekTsqG/AGuLLngTdgoviGTSGrtfxBQA4Bj46AEu6c13tUMBYttIN9wXDQe0SnL4ZWPO/AOf+XsPu+3psELC5Sxk45cHawkNDK0V380upYxRIq1PzBtVSNUuyDChBbe5sc3AFt5TRYx7FWj4UOyvEQKOr6gkvz3ezKs1eOh0KhcClruJfiIVC5x62SaMWw61n5KVUvUujO9AEj6NYI3t6pwJ0lMwW4E8reJAGHXilt7h8sn9noRgBXGm1VXDtUW8/vM/Yt+XOwjqvWh+s1+dgnOkHkW28jUN3EGyjjZi/Z8DWEanv7CZtSkDlF8nFQFaeAxZwx6T+JUACzgX8AzhsRLr/8boIAAAAASUVORK5CYII='/></button></div>
                        <div class='delete'><button onclick="showConfirmationPopup(<?php echo $result['id']; ?>)"><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAABM0lEQVRIS+2W3RWCMAyFb5zAERjBDQQnkAnEDdxAncAR1A2YQOsGbqAjMIH1WlHhHH5KecCH9okDTb/kNiQRDLRkIC46gXWIFR0d1zkrChvbQKzBBirYNR4sOMgJSxu4FZjQhNC9zYHQiBl52ra3BCYgJODcZuT0XSOiQ+pj+zfggB4lThG1Gx0Y8b0y4qItZZ80ZXA7x+zICLtW7a1NLh2Zuw4tAXXblJwRefBLAXupNbZGMsG04goU/99L/n1dkLa/1Lwr4ySTbsOn4uF8ie2nXDI3tAf3ymovdUk+n1w/Ofx//NWiS8l8d5kRFqxNSSm5OGvhgWNeMosTTH+pHdujA3jGiVKbcdZ9CVJOnXG3yhUiYDO4uVOR5ROn6gTOO9Fr/Jk7wDPaqLqx591dB1qDgZ8/Hckf0D/hYQAAAABJRU5ErkJggg=='/></a></div></div>
                        <!-- Ajoutez la fenêtre pop-up de confirmation -->
                        <div id="confirmationPopup<?php echo $result['id']; ?>" class="confirmation-popup">
                            <p>Tu es sur de vouloir supprimer cette évènement ?</p>
                            <div>
                            <button class="oui" onclick="confirmSuppression()">Oui</button>
                            <button class="non" onclick="hideConfirmationPopup()">Non</button>
                            </div>
                        </div>
                    <?php endif ?>
                    <script>
                        //permet d'enlever la date quand le titre passe dessus
                        function adjustVisibility(){
                            var date = "<?php echo $dateId; ?>";
                            var titre = "<?php echo $titreId; ?>";
                            var dateElement = document.getElementById(date);
                            var titleElement = document.getElementById(titre);
                            
                            var dateRect = dateElement.getBoundingClientRect();
                            var titleRect = titleElement.getBoundingClientRect();

                            if (dateRect.right > titleRect.left) {
                                // La date se superpose avec le titre, masquer la date
                                dateElement.style.visibility = 'hidden';
                            } else {
                                // La date ne se superpose pas avec le titre, afficher la date
                                dateElement.style.visibility = 'visible';
                            }
                        }
                        window.addEventListener('resize', adjustVisibility);
                        window.addEventListener('load', adjustVisibility);
                        
                    </script>
                    <?php
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            }
            else{   
                echo'<span style="color: red;">Erreur excution base de donnée';
            }
        ?>

    </main>
    <footer class="footer-distributed">

      <div class="footer-left">
  
        <h3>ESTIA<span>SYSTEM</span></h3>
  
        <p class="footer-links">
            <a href="accueil.html" class="link-1">Menu</a>
            <a href="apropos.html">A Propos</a>
            <a href="project.html">Projets</a>
            <a href="evenements.php">Évènements</a>
            <a href="contact.html">Contact</a>
          </p>
  
        <p class="footer-company-name">ESTIA SYSTEM © 2024</p>
      </div>
  
      <div class="footer-center">
  
        <div>
          <i class="fa fa-map-marker"></i>
          
          <p><span>97 All. Théodore Monod</span>64210 Bidart</p>
        </div>
  
      
  
        <div>
          <i class="fa fa-envelope"></i>
          <p><a href="mailto:estiasystem@net.estia.fr">estiasystem@net.estia.fr</a></p>
        </div>
  
      </div>
  
      <div class="footer-right">
  
        <p class="footer-company-about">
          <span>A propos de l'association</span>
          Estia System accueille tout le monde et réalise des projets variés avec une approche inclusive.
        </p>
  
        <div class="footer-icons">
  
          <a href="https://www.facebook.com/estiasystem/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(24, 119, 242, 1);"><path d="M12.001 2.002c-5.522 0-9.999 4.477-9.999 9.999 0 4.99 3.656 9.126 8.437 9.879v-6.988h-2.54v-2.891h2.54V9.798c0-2.508 1.493-3.891 3.776-3.891 1.094 0 2.24.195 2.24.195v2.459h-1.264c-1.24 0-1.628.772-1.628 1.563v1.875h2.771l-.443 2.891h-2.328v6.988C18.344 21.129 22 16.992 22 12.001c0-5.522-4.477-9.999-9.999-9.999z"></path></svg></a>
          <a href="https://twitter.com/estiasystem?lang=fr" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(56, 168, 224, 1)"><path d="M19.633 7.997c.013.175.013.349.013.523 0 5.325-4.053 11.461-11.46 11.461-2.282 0-4.402-.661-6.186-1.809.324.037.636.05.973.05a8.07 8.07 0 0 0 5.001-1.721 4.036 4.036 0 0 1-3.767-2.793c.249.037.499.062.761.062.361 0 .724-.05 1.061-.137a4.027 4.027 0 0 1-3.23-3.953v-.05c.537.299 1.16.486 1.82.511a4.022 4.022 0 0 1-1.796-3.354c0-.748.199-1.434.548-2.032a11.457 11.457 0 0 0 8.306 4.215c-.062-.3-.1-.611-.1-.923a4.026 4.026 0 0 1 4.028-4.028c1.16 0 2.207.486 2.943 1.272a7.957 7.957 0 0 0 2.556-.973 4.02 4.02 0 0 1-1.771 2.22 8.073 8.073 0 0 0 2.319-.624 8.645 8.645 0 0 1-2.019 2.083z"></path></svg></a>
          <a href="https://www.instagram.com/estiasystem/?hl=fr" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M11.999 7.377a4.623 4.623 0 1 0 0 9.248 4.623 4.623 0 0 0 0-9.248zm0 7.627a3.004 3.004 0 1 1 0-6.008 3.004 3.004 0 0 1 0 6.008z"></path><circle cx="16.806" cy="7.207" r="1.078"></circle><path d="M20.533 6.111A4.605 4.605 0 0 0 17.9 3.479a6.606 6.606 0 0 0-2.186-.42c-.963-.042-1.268-.054-3.71-.054s-2.755 0-3.71.054a6.554 6.554 0 0 0-2.184.42 4.6 4.6 0 0 0-2.633 2.632 6.585 6.585 0 0 0-.419 2.186c-.043.962-.056 1.267-.056 3.71 0 2.442 0 2.753.056 3.71.015.748.156 1.486.419 2.187a4.61 4.61 0 0 0 2.634 2.632 6.584 6.584 0 0 0 2.185.45c.963.042 1.268.055 3.71.055s2.755 0 3.71-.055a6.615 6.615 0 0 0 2.186-.419 4.613 4.613 0 0 0 2.633-2.633c.263-.7.404-1.438.419-2.186.043-.962.056-1.267.056-3.71s0-2.753-.056-3.71a6.581 6.581 0 0 0-.421-2.217zm-1.218 9.532a5.043 5.043 0 0 1-.311 1.688 2.987 2.987 0 0 1-1.712 1.711 4.985 4.985 0 0 1-1.67.311c-.95.044-1.218.055-3.654.055-2.438 0-2.687 0-3.655-.055a4.96 4.96 0 0 1-1.669-.311 2.985 2.985 0 0 1-1.719-1.711 5.08 5.08 0 0 1-.311-1.669c-.043-.95-.053-1.218-.053-3.654 0-2.437 0-2.686.053-3.655a5.038 5.038 0 0 1 .311-1.687c.305-.789.93-1.41 1.719-1.712a5.01 5.01 0 0 1 1.669-.311c.951-.043 1.218-.055 3.655-.055s2.687 0 3.654.055a4.96 4.96 0 0 1 1.67.311 2.991 2.991 0 0 1 1.712 1.712 5.08 5.08 0 0 1 .311 1.669c.043.951.054 1.218.054 3.655 0 2.436 0 2.698-.043 3.654h-.011z"></path></svg></a>
          <a href="https://www.linkedin.com/company/estiasystem/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(0, 122, 185, 1);"><path d="M20 3H4a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM8.339 18.337H5.667v-8.59h2.672v8.59zM7.003 8.574a1.548 1.548 0 1 1 0-3.096 1.548 1.548 0 0 1 0 3.096zm11.335 9.763h-2.669V14.16c0-.996-.018-2.277-1.388-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248h-2.667v-8.59h2.56v1.174h.037c.355-.675 1.227-1.387 2.524-1.387 2.704 0 3.203 1.778 3.203 4.092v4.71z"></path></svg></a>
          <a href="https://github.com/ESTIASYSTEM" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.026 2c-5.509 0-9.974 4.465-9.974 9.974 0 4.406 2.857 8.145 6.821 9.465.499.09.679-.217.679-.481 0-.237-.008-.865-.011-1.696-2.775.602-3.361-1.338-3.361-1.338-.452-1.152-1.107-1.459-1.107-1.459-.905-.619.069-.605.069-.605 1.002.07 1.527 1.028 1.527 1.028.89 1.524 2.336 1.084 2.902.829.091-.645.351-1.085.635-1.334-2.214-.251-4.542-1.107-4.542-4.93 0-1.087.389-1.979 1.024-2.675-.101-.253-.446-1.268.099-2.64 0 0 .837-.269 2.742 1.021a9.582 9.582 0 0 1 2.496-.336 9.554 9.554 0 0 1 2.496.336c1.906-1.291 2.742-1.021 2.742-1.021.545 1.372.203 2.387.099 2.64.64.696 1.024 1.587 1.024 2.675 0 3.833-2.33 4.675-4.552 4.922.355.308.675.916.675 1.846 0 1.334-.012 2.41-.012 2.737 0 .267.178.577.687.479C19.146 20.115 22 16.379 22 11.974 22 6.465 17.535 2 12.026 2z"></path></svg></a>
  
  
        </div>
  
      </div>
      <div class="footer-bottom">
        <p>Ce site est la propriété de l'association ESTIA SYSTEM</p>
      </div>
  
  </footer>
    <script>
        function editElement(elementId) {
            window.location.href = 'edit.php?id=' + elementId;
        }

        function showConfirmationPopup(idElement) {
            // Affiche la fenêtre pop-up
            document.getElementById('confirmationPopup'+idElement).style.display = 'block';

            // Stocke l'ID de l'élément dans une variable globale pour une utilisation ultérieure
            window.elementIdToDelete = idElement;
        }

        function hideConfirmationPopup() {
            // Cache la fenêtre pop-up
            document.getElementById('confirmationPopup'+window.elementIdToDelete).style.display = 'none';
        }

        function confirmSuppression() {
            // Redirige vers le script de traitement de la suppression avec l'ID de l'élément
            window.location.href = 'delete.php?id=' + window.elementIdToDelete;
        }
    </script>
    <script src="youtube.js"></script>
</body>
</html>
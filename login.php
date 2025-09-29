<?php

if(!$_SESSION['LOGGED_USER']) : ?>
<form name="logs_form" method="POST" action="submit_login.php" data-ajax="false">
    <p>
        <label for="email">Email:</label>
        <input class="texte" name="email" id="email" value="" type="text" required>
    </p>
    <p>
        <label for="logs_pass">Mot de passe:</label>
        <input class="texte" name="logs_pass" id="logs_pass" value="" autocomplete="on" type="password" required>
    </p>
    <p>
        <input id="bouton" value="Ok !" name="logs_submit" id="logs_submit" type="submit">
    </p>
    <?php if(isset($_SESSION['ERROR_MESSAGE'])) : ?>
    <div id='erreur'>Mot de passe incorrect</div>
    <?php endif ?>
</form>
<?php endif ?>
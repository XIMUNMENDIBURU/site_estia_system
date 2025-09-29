<?php
    session_start();

    include_once("config.php");
    $_SESSION['LOGGED_USER']=0;

    if(isset($_POST["email"]) && isset($_POST["logs_pass"])) {
        $requestLog = "SELECT * FROM users";

        $statementLog = $baseD->prepare($requestLog);

        if($statementLog->execute()){
            $users =$statementLog->fetchAll();
            foreach($users as $user){
                if($user["email"]==$_POST["email"] && $user["password"]==md5($_POST["logs_pass"])){
                    $_SESSION['LOGGED_USER']=1;
                    $_SESSION['ERROR_MESSAGE']=0;
                }
                else{
                    $_SESSION['LOGGED_USER']=0;
                    $_SESSION['ERROR_MESSAGE']=1;
                }
            }
        }
        header("Location: adminES.php");
        exit();
    }

    
?>
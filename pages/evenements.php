<?php
session_start();

// Handle login
$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    require_once __DIR__ . '/../config/db.php';
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['LOGGED_USER'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: adminES.php');
        exit();
    } else {
        $loginError = 'Identifiant ou mot de passe incorrect';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ESTIA SYSTEM est une association étudiante de l'ESTIA, proposant des projets et des évènements dans le domaine de l'électronique, la mécanique et l'informatique">
    <meta name="keywords" content="Robotique, ESTIA, ESTIA SYSTEM, Mécatronique, Coupe de France de Robotique, CFR, Electronique, Informatique, Mécanique, Robot, Association">
    <link rel="stylesheet" href="../assets/css/legacy-mise-en-pages.css">
    <link rel="shortcut icon" href="../images/Logo_Estia_System/logoblanc.ico">
    <title>ESTIA SYSTEM - Évènements</title>
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .login-container h2 {
            color: #1e40af;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn-login:hover {
            background: #1e40af;
        }
        
        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body class="accueil__page2">
    <?php require_once __DIR__ . '/../includes/header.php'; ?>
    
    <main>
        <div class="apropos_asso">
            <h2>Évènements</h2>
            <p class="presentation">Page en cours de construction</p>
        </div>
        <div class="login-container">
            <h2>Connexion Administrateur</h2>
            
            <?php if ($loginError): ?>
                <div class="error-message"><?php echo htmlspecialchars($loginError); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Identifiant</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" name="login" class="btn-login">Se connecter</button>
            </form>
        </div>
    </main>
    
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>

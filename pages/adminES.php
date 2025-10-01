<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['LOGGED_USER']) || $_SESSION['LOGGED_USER'] == 0) {
    header('Location: evenements.php');
    exit();
}

require_once __DIR__ . '/../config/db.php';
$db = Database::getInstance()->getConnection();

// Handle user creation
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $identifiant = trim($_POST['identifiant'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($nom) || empty($prenom) || empty($identifiant) || empty($password)) {
        $errorMessage = 'Tous les champs sont obligatoires';
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO users (nom, prenom, identifiant, password, created_by) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $identifiant, $password, $_SESSION['LOGGED_USER']]);
            $successMessage = 'Utilisateur créé avec succès';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errorMessage = 'Cet identifiant existe déjà';
            } else {
                $errorMessage = 'Erreur lors de la création: ' . $e->getMessage();
            }
        }
    }
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'] ?? 0;
    try {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $successMessage = 'Utilisateur supprimé avec succès';
    } catch (PDOException $e) {
        $errorMessage = 'Erreur lors de la suppression: ' . $e->getMessage();
    }
}

// Get all users
$stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/legacy-mise-en-pages.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="shortcut icon" href="../images/Logo_Estia_System/logoblanc.ico">
    <title>ESTIA SYSTEM - Administration</title>

</head>

<body class="accueil__page2">
    <?php require_once __DIR__ . '/../includes/header.php'; ?>
    
    <main>
        <div class="admin-container">
            <div class="admin-header">
                <h1>Administration - Gestion des Utilisateurs</h1>
                <a href="logout.php" class="btn-logout">Déconnexion</a>
            </div>
            
            <?php if ($successMessage): ?>
                <div class="message success"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            
            <?php if ($errorMessage): ?>
                <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            
            <!-- Updated form to only have 4 fields: nom, prenom, identifiant, password -->
            <div class="admin-section">
                <h2>Créer un nouvel utilisateur</h2>
                <form method="POST" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="identifiant">Identifiant *</label>
                            <input type="text" id="identifiant" name="identifiant" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Mot de passe *</label>
                            <input type="text" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="create_user" class="btn-primary">Créer l'utilisateur</button>
                </form>
            </div>
            
            <!-- Updated table to show nom, prenom, identifiant, password -->
            <div class="admin-section">
                <h2>Liste des utilisateurs (<?php echo count($users); ?>)</h2>
                
                <?php if (empty($users)): ?>
                    <div class="no-users">Aucun utilisateur créé pour le moment</div>
                <?php else: ?>
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Identifiant</th>
                                <th>Mot de passe</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($user['identifiant']); ?></td>
                                    <td><?php echo htmlspecialchars($user['password']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" name="delete_user" class="btn-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>

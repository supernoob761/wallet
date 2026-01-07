<?php
/**
 * Action: Connexion
 * Traite le formulaire de connexion
 */

require __DIR__ . '/../config/bootstrap.php';


use Module\User;
// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/auth/login.php');
    exit();
}

// Vérifier le token CSRF
if (!isset($_POST['csrf_token']) || !Module\Security::verifyCSRFToken($_POST['csrf_token'])) {
    $_SESSION['login_error'] = 'Token de sécurité invalide';
    header('Location: ../view/auth/login.php');
    exit();
}

// Récupérer et nettoyer les données
$email = Module\Security::clean($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validation
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Veuillez remplir tous les champs';
    header('Location: ../view/auth/login.php');
    exit();
}

if (!Module\Security::validateEmail($email)) {
    $_SESSION['login_error'] = 'Email invalide';
    header('Location: ../view/auth/login.php');
    exit();
}

// Tentative de connexion
$user = new Module\User();
$result = $user->login($email, $password);

if ($result) {
    // Rediriger selon le rôle
        header('Location: ../view/index.php');

    exit();
} else {
    $_SESSION['login_error'] = 'Email ou mot de passe incorrect';
    header('Location: ../view/auth/login.php');
    exit();
}
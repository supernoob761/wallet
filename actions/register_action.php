<?php
require __DIR__ . '/../config/bootstrap.php';


use Module\User;

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/auth/register.php');
    exit();
}

// Vérifier le token CSRF
if (!isset($_POST['csrf_token']) || !Module\Security::verifyCSRFToken($_POST['csrf_token'])) {
    $_SESSION['register_error'] = 'Token de sécurité invalide';
    header('Location: ../view/auth/register.php');
    exit();
}

// Récupérer et nettoyer les données
$nom = Module\Security::clean($_POST['nom'] ?? '');
$email = Module\Security::clean($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$role = Module\Security::clean($_POST['role'] ?? 'enseignant');

// Validation
if (empty($nom) || empty($email) || empty($password) || empty($confirmPassword)) {
    $_SESSION['register_error'] = 'Veuillez remplir tous les champs';
    header('Location: ../view/auth/register.php');
    exit();
}

if (!Module\Security::validateEmail($email)) {
    $_SESSION['register_error'] = 'Email invalide';
    header('Location: ../view/auth/register.php');
    exit();
}

if (!Module\Security::validatePassword($password)) {
    $_SESSION['register_error'] = 'Le mot de passe doit contenir au moins 8 caractères';
    header('Location: ../view/auth/register.php');
    exit();
}

if ($password !== $confirmPassword) {
    $_SESSION['register_error'] = 'Les mots de passe ne correspondent pas';
    header('Location: ../view/auth/register.php');
    exit();
}

// Créer l'utilisateur
$user = new User();
$result = $user->createUser($nom, $email, $password);

if ($result) {
    $_SESSION['register_success'] = 'Compte créé avec succès ! Vous pouvez vous connecter.';
    header('Location: ../view/auth/login.php');
    exit();
} else {
    $_SESSION['register_error'] = 'Erreur lors de la création du compte. Email déjà utilisé ?';
    header('Location: ../view/auth/register.php');
    exit();
}
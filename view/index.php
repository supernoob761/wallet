<?php

require __DIR__ . '/../config/bootstrap.php';  // better than separate database + autoload

use Module\User;
use Module\Security;
use Module\Categories;

// Redirect if not logged in
if (!Security::isLoggedIn()) {
    header('Location: auth/login.php');
    exit();
}


$user = new User();
$users = $user->getAll();

echo "<pre>";
print_r($users);
echo "</pre>";

$category = new Module\Categories;
$categories = $category->getAll();

echo "<pre>";
print_r($categories);
echo "</pre>";


?>
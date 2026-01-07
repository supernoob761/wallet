<?php

require __DIR__ . '/../../config/bootstrap.php';

use Module\User;
use Module\Security;


if (Security::isLoggedIn()) {
    $user = new User();
    $user->logout();
}

header('Location: login.php');
exit();

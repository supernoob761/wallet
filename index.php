<?php

require __DIR__ . '/vendor/autoload.php';


use Module\User;

$User = new User();
$UserCon = new Controller\User();

echo $User->getName();
echo $UserCon->getName();
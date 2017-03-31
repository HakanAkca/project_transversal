<?php

require_once('User.php');

$user1 = new User('Krayorn', 'nath.arki@nath.fr', 'hashed', 'Paris');

print($user1->getPass());
print($user1->getEmail());
$user1->setEmail('newEmail@random');
print($user1->getEmail());
var_dump($user1);
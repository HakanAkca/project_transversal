<?php

require_once('User.php');

$user1 = new User('nath.arki@nath.fr', 'hashed');

print($user1->getPass());
print($user1->getEmail());
$user1->setEmail('newEmail@random');
print($user1->getEmail());
$user1->setFirstName('Jean');
$user1->setLastName('Dupont');
print($user1->getFirstName());
print($user1->getLastName());
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/foxholder-styles.css" />
    <link href="web/register.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="assets/foxholder.js"></script>
    <script src="assets/register.js"></script>
</head>
<body>
<div id="nav_bar">
    <div id="logo"></div>
    <div id="stats">
        <span>558 524</span> Objets recyclés
    </div>
    <div id="nav">
        <a>Home</a>
        <a>Nous</a>
        <a href="?action=partner" >Devenir Partenaires</a>
    </div>
    <div id="current">
        <a>Inscription</a>
    </div>
</div>
<div id="title">
    INSCRIPTION
</div>
<div id="line"></div>
<!--<form id="myRegister" class="form" method="POST" action="?action=register">
        <input type="text" name="username" id="first-input-1" placeholder="Pseudo*"><br>
        <input type="email" name="email" placeholder="Mail*"><br>
        <input type="password" name="password" placeholder="Mot de Passe*"><br>
        <input type="password" name="verifpassword" placeholder="Confirmation Mot de Passe*"><br>
        <input type="text" name="city" placeholder="Ville*"><br>
        <button type="submit" id="button">Créer mon compte</button>
    </form>-->
<div class="form-container-12 form-container form" id="example-12">
    <form id="myRegister" method="POST" action="?action=register">
        <input id="username" type="text" name="username" placeholder="Pseudo*">
        <input id="email" type="email" name="email" placeholder="Mail*">
        <input id="password" type="password" name="password" placeholder="Mot de Passe*">
        <input id="verifpassword" type="password" name="verifpassword" placeholder="Confirmation Mot de Passe*">
        <input id="city" type="text" name="city" placeholder="Ville*">
        <button type="submit" id="button">Créer mon compte</button>
    </form>
</div>
</body>
</html>
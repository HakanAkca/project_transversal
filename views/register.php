<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register</title>
        <meta charset="UTF-8">
        <link href="web/register.css" rel="stylesheet">
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
        <form method="POST" action="?action=register" class="form">
            <input type="text" name="username" placeholder="Pseudo*"><br>
            <input type="email" name="email" placeholder="Mail*"><br>
            <input type="password" name="password" placeholder="Mot de Passe*"><br>
            <input type="password" name="verifpassword" placeholder="Confirmation Mot de Passe*"><br>
            <input type="text" name="city" placeholder="Ville*"><br>
            <input type="submit" value="Créer mon compte" id="button" >
        </form>
    </body>
</html>
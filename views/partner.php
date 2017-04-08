<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Partner</title>
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
                <a class="clicked">Devenir Partenaires</a>
            </div>
            <div id="current">
                <a>Inscription</a>
            </div>
        </div>
        <div id="title">
           DEVENIR PARTENAIRE
        </div>
        <div id="line"></div>
        <form method="POST" action="?action=partner" class="form">
            <input type="text" name="name" placeholder="Name*"><br>
            <input type="email" name="email" placeholder="Mail*"><br>
            <input type="text" name="city" placeholder="Ville*"><br>
            <input type="text" name="phone" placeholder="Téléphone*"><br>
            <input type="text" name="statut" placeholder="Statut*"><br>
            
            <input type="submit" value="Devenir Partenaire" id="button" >
        </form>
    </body>
</html>
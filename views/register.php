<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <div class="form">  
            <fieldset>
            <legend>Inscription</legend>
                <form method="POST" action="?action=register">
                    <label for="username">Nom d'utilisateur : </label><input required type="text" name="username" id="username"><br>
                    <label for="password">Mot de passe : </label><input required type="password" name="password" id="password"><br>
                    <label for="email">E-mail : </label><input required type="email" name="email" id="email"><br>
                    <label for="verifpassword">Confirmation du mot de passe : </label><input required type="password" name="verifpassword" id="verifpassword"><br>
                    <label for="city">City : </label><input required type="text" name="city" id="city"><br>
                    <input type="submit" value="S'inscrire">
                </form>
            </fieldset>   
        </div>
    </body>
</html>
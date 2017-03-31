<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Incription</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="assets/register.css" rel="stylesheet">
    </head>
    <body>
        <form method="POST" action="?action=inscription">
            <input required type="text" name="username" placeholder="Pseudo"><br>
            <input required type="email" name="email" placeholder="Email"><br>
            <input required type="password" name="password" placeholder="Password"><br>
            <input required type="password" name="verifpassword" placeholder="Type your password again"><br>
            <input required type="text" name="city" placeholder="City ex:Paris, Lilles"><br>
            <input type="submit" value="S'inscrire">
        </form>
    </body>
</html>
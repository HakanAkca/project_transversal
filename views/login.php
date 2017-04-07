<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <link rel="stylesheet" href="web/style/style.css">
    </head>
    <body>
        <div id="container">
            <?php require('header.php'); ?>
            <div id="containe_box">
                <?php if (!empty($error)): ?>
                <p>Error : <?php echo $error ?></p>
                <?php endif; ?>
                <form action="?action=login" method="POST">
                    <h1>Sign Up</h1>
                
                    <fieldset>
                        <label for="username">Nom d'utlisateur <span class="star">*</span></label>
                        <input type="text" id="username" name="username">
                              
                        <label for="password">Mot de passe <span class="star">*</span></label>
                        <input type="password" id="password" name="password">
                    </fieldset>
                    <button type="submit">Sign In</button>
                    <p id="or">OR</p>
                    <h1><a id="register" href='?action=register'>Register</a></h1>
                </form>
            </div>
        </div>
    </body>
</html>

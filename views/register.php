<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Register</title>
        <link rel="stylesheet" href="web/style/style.css">
    </head>
    <body>
      <div id="container">
        <?php require('header.php'); ?>
        <div id="containe_box">
          <?php if (!empty($error)): ?>
          <p>Error : <?php echo $error ?></p>
          <div id='errorBlock'></div>
          <?php endif; ?>
          <form action="?action=register" method="POST" name="form" id="register-form">
              <h1>Sign Up</h1>
          
              <fieldset>
              
                  <label for="firstname">Nom</label>
                  <input type="text" id="firstname" name="firstname">

                  <label for="lastname">Prénom</label>
                  <input type="text" id="lastname" name="lastname">

                  <label for="username">Nom d'utlisateur <span class="star">*</span></label>
                  <input type="text" id="username" name="username">
            
                  <span id='spanVerifPassword'></span>
                  <span id="#comparePassword"></span>
                  <span id="#spanPassword"></span>
                  <label for="password">Mot de passe <span class="star">*</span></label>
                  <input type="password" id="password" name="password">

                  <label for="repeat_password">Confirmation mot de passe <span class="star">*</span></label>
                  <input type="password" id="repeat_password" name="repeat_password">
            
                  <label for="job">Question-Réponse <span class="star">*</span></label>
                  <select id="ask" name="secret_ask">
                    <option value="frontend_developer">Front-End Developer</option>
                    <option value="php_developor">PHP Developer</option>
                    <option value="python_developer">Python Developer</option>
                    <option value="rails_developer"> Rails Developer</option>
                    <option value="web_designer">Web Designer</option>
                    <option value="WordPress_developer">WordPress Developer</option>
                </select>
                <input type="text" id="secret_answer" placeHolder="Votre réponse..." name="secret_answer">
              </fieldset>
              <button type="submit">Sign Up</button>
          </form>
        </div>
      </div>  
    </body>
</html>

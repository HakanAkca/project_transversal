<!DOCTYPE html>
<?php chdir(); ?>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="web/style/style.css">
    <script src="web/script/script.js"></script>
</head>
<body>
  <div id="container">
    <?php require('header.php'); ?>
    <div id="containe_box">
       <div id="params">
        <div id="add_file_box">
            <?php 
            require_once('model/profil.php');
            if(!empty($add_file_succes))
                echo $add_file_succes.'<br>';
            if(!empty($folder))
                echo $folder.'<br>';
            if(!empty($move_file))
                echo $move_file.'<br>';
            ?>
            <h5 id='add_file_or_folder'><span id='add_folder'><img src='web/img/folder.png' alt='settings'/>&nbsp;&nbsp;Créer un dossier</span><span id='add_file'><img src='web/img/upload.png' alt='settings'/>&nbsp;&nbsp;Ajouter un fichier</span></h5>
            <div id='form_for_add_file'>
                <p class='welcome'><b>Formats acceptés : </b>pdf, jpg, jpeg, png, txt</p>
                <form action="?action=profil" method="POST" enctype="multipart/form-data">
                    <input type="file" name="add_file"><br>
                    <input type="text" name="nickname" placeHolder="nommer le fichier (Laisser vide sinon)">
                    <?php 
                        if(is_array($allmydirectory) && !empty($allmydirectory)){
                            echo "<select id='ask' name='directory'>";
                            echo "<option value='ajouter_dans_un_dossier'>Ajouter dans un dossier</option>";
                            foreach ($allmydirectory as $key => $value)
                            {
                                if(is_array($value)){
                                    echo "<option value='".$key."'>".$key."</option>";
                                }
                            }
                            echo "</select>";
                        }
                    ?>    
                    <label for='input_add_file_none'><img class='others_icons' src='web/img/upload2.png' ></label><input id='input_add_file_none' class='none' type="submit" name="sumbit_add_file" value="Ajouter">

                </form>
            </div>    
            <div class='none' id='form_for_add_folder'>
                <form action="?action=profil" method="POST">
                    <input type="text" name="new_folder_name" placeHolder="Nom du dossier">
                    <input type="submit" name="sumbit_add_folder" value="Créer dossier">
                </form>
            </div>  
        </div>
        <div id="other_params">
            <h5 id="other_params_bis"><img src='web/img/settings.png' alt='settings'/>&nbsp;&nbsp;Autres paramètres</h5>    
            <div id="other_params_box">
                <h6><span id='rename_file'><img src='web/img/rename.png' alt='rename'/>&nbsp;&nbsp;Renommer un fichier:</span>
                    <span><img src='web/img/move.png' alt='move'/>&nbsp;&nbsp;Deplacer un fichier:</span>
                    <span id='replace_file'><img src='web/img/replace.png' alt='move'/>&nbsp;&nbsp;Remplacer un fichier:</span></h6>
                </div>
                <div class='none' id='form_for_replace_file'>
                    <form action="?action=profil" method="POST" enctype="multipart/form-data">
                        <input type="file" name="file">
                        <?php if(is_array($allmydirectory) && !empty($allmydirectory)){
                                echo "<select id='ask' name='directory_replace'>";
                                echo "<option value='Racine'>Dossier courant</option>";
                                foreach ($allmydirectory as $key => $value)
                                {
                                    if(is_array($value)){
                                        echo "<option value='".$key."'>".$key."</option>";
                                    }
                                }
                                echo "</select>";
                            }
                        ?>          
                        <input type="text" name="file_name_to_replace" placeHolder="nom du fichier a replacer">
                        <label for='input_replace_file_none'><img class='others_icons' src='web/img/replace2.png' ></label><input type="submit" class='none' id='input_replace_file_none' name="submit_replace_file" value="remplacer">
                    </form>
                </div>   
            </div>
        </div>
        <div id="my_files">
            <div id="all_folders">
                <?php 
                    if(is_array($allmydirectory) && !empty($allmydirectory)){
                        foreach ($allmydirectory as $key => $value)
                        {
                            if(is_array($value)){
                                if(!empty($value)){
                                    echo "<p><img src='web/img/folder2.png' alt='folder'/>&nbsp;&nbsp;".$key."</p>";
                                    foreach ($value as $key2 => $value2) {
                                        echo "<p class='sous_dossier'><img src='web/img/file.png' alt='folder'/>&nbsp;&nbsp;".$value2."</p>";
                                    }
                                }else{
                                    echo "<p><img src='web/img/folder3.png' alt='folder'/>&nbsp;&nbsp;".$key."</p>";
                                }
                            }else{
                                echo "<p><img src='web/img/file.png' alt='folder'/>&nbsp;&nbsp;".$value."</p>";
                            }
                        }
                        if(is_array($allmydirectory) && !empty($allmydirectory)){
                           
                            echo "<form action='?action=profil' method='POST'>";
                            echo "<select id='ask' name='old_folder_name'>";
                                //echo "<option value='Racine'>Racine</option>";
                                    foreach ($allmydirectory as $key => $value)
                                    {
                                        if(is_array($value)){
                                            echo "<option value='".$key."'>".$key."</option>";
                                        }
                                    }
                            echo "</select>
                                    <input type='text' name='new_folder_name'>
                                    <input type='submit' name='submit_rename_folder' value='renommer doosier'>
                                    </form>";
                                    echo "<form action='?action=profil' method='POST'>";
                            echo "<pSupprimer un dossier</p>";
                            echo "<select id='ask' name='name_folder_to_delete'>";
                                //echo "<option value='Racine'>Racine</option>";
                                    foreach ($allmydirectory as $key => $value)
                                    {
                                        if(is_array($value)){
                                            echo "<option value='".$key."'>".$key."</option>";
                                        }
                                    }
                            echo "</select>
                                    <input type='submit' name='submit_delete_folder' value='Supprimer dossier'>
                                    </form>";

                        }            

                    }     
                ?>
            </div>
            <div id="all_folders_box">
            <?php
                foreach($my_files as $file){
                    $img = '';
                    $edit = '';
                    $textarea = ''; 

                    if($formats[$file['file_name']] == '.jpeg' || $formats[$file['file_name']] == '.jpg'
                            || $formats[$file['file_name']] == '.png')
                        $img = "<img class='img' src='".$file['file_url']."' alt='img'/>";  
                    else if($formats[$file['file_name']] == '.txt'){
                        $img = "<img class='img' src='web/img/txt.png' alt='img'/>";
                        $edit = "<span class='edit_icon'><img title='Edit' src='web/img/edit.png' title='edit' alt='edit'/></span>";
                        $textarea = "<div class='textarea_content'>
                                        <textarea name='txt_content'>".file_get_contents($file['file_url'])."</textarea>
                                        <input class='none' type='text' name='url_txt' value='".$file['file_url']."'>   
                                        <input type='submit' name='modif_txt' value='Sauvegarder'>
                                    </div>";
                    }   
                    else if($formats[$file['file_name']] == '.mp3')
                        $img = "<audio src='".$file['file_url']."' controls></audio>";
                    else if($formats[$file['file_name']] == '.mp4')
                        $img = "<video src='".$file['file_url']."' controls></video>";
                    else if($formats[$file['file_name']] == '.pdf')
                        $img = "<embed src='".$file['file_url']."'/>";
                    $none = 'dossier';
                    if(strlen($file['file_url'])>(strlen($_SESSION['user_username'])+9))
                        $dossier = substr($file['file_url'], strlen($_SESSION['user_username'])+ 9,-(strlen($file['file_name'])+1)); 
                    if($dossier == '')
                        $none = 'none';
                    echo "<div class='files'>";
                    echo "<span><img src='web/img/user.png' alt='user'/>&nbsp;&nbsp;".$_SESSION['user_username']."</span>";
                    echo $img;
                    echo "<form action='?action=profil' method='POST'>
                    <div class='file_name'>".$file['file_name']."</div>
                    <div class='icons'>
                        <div class='icons_box'>    
                            <span><label for='".$file['file_url']."'><img title='supprimer' src='web/img/delete.png' title='supprimer' alt='settings'/></label></span>
                            <span><a href='".$file['file_url']."' download><img src='web/img/download.png' alt='download'/></a></span>
                            ".$edit."
                            <span class='move_icon' ><img title='move' src='web/img/move.png' alt='move'/></span>
                            <span class='rename_icon' ><img title='rename' src='web/img/rename.png' alt='rename'/></span>
                        </div>
                        <div class='".$none."'><img src='web/img/directory.png' alt='settings'/>&nbsp;&nbsp;".$dossier."</div>
                    </div>
                    <div class='none'>
                        <input  type='text' name='file_to_delete' value='".$file['file_url']."'>
                        <input  type='submit' name='submit_delete_file' id='".$file['file_url']."'>
                    </div>
                    <div class='rename_box'>
                        <input class = 'none' type='text' name='current_file_name' value='".$file['file_name']."'>
                        <input class='none' type='text' name='file_to_rename' value='".$file['file_url']."'>
                        <input type='text' name='new_file_name' placeHolder='Nouveau nom du fichier'>
                        <input type='submit' name='submit_rename_file'>
                    </div>    
                    <div class='modif_txt_box'>
                    ".$textarea."
                    </div>"; ?>
                    <?php 
                        echo "<div class='move_box'>"; 
                        if(is_array($allmydirectory) && !empty($allmydirectory)){
                            echo "<select id='ask' name='directory_move'>";
                            echo "<option value='Racine'>Racine</option>";
                            foreach ($allmydirectory as $key => $value)
                            {
                                if(is_array($value)){
                                    echo "<option value='".$key."'>".$key."</option>";
                                }
                            }
                            echo "</select>";
                        }            
                echo "<input class='none' type='text' name='name_file_to_move' value='".$file['file_name']."'>";                        
                echo "<input class='none' type='text' name='url_file_to_move' value='".$file['file_url']."'>";            
                echo "<input type='submit' name='submit_move_file' value='Déplacer'>";
                echo "</div>";
                echo "</form>";
                echo "</div>";
            }
         ?>
    </div>     
 </div>
</div>
           
                
                
                
        
</div>
</body>
</html>        
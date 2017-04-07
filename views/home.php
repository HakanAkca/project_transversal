<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Home page</title>
        <link rel="stylesheet" href="web/style/style.css">
    </head>
    <body>
    	<div id="container">
    	
        	<div id="containe_box">
        		<p>Hello <?php echo $username ?>, allez sur <a href='?action=profil'>'My files'</a> pour ajouter, supprimer ou modifier un fichier</p>
        	    
                    
              <?php  	
            echo "<div id='my_files'>"; 
            $cpt2 = 0;
            foreach($all_files as $file){
                $img = '';
                if($formats[$file['file_name']] == '.jpeg' || $formats[$file['file_name']] == '.jpg'
                        || $formats[$file['file_name']] == '.png')
                    $img = "<img class='img' src='".$file['file_url']."' alt='img'/>";  
                else if($formats[$file['file_name']] == '.txt')
                    $img = "<img class='img' src='web/img/txt.png' alt='img'/>";
                else if($formats[$file['file_name']] == '.pdf')
                    $img = "<embed src='".$file['file_url']."'/>";
                else if($formats[$file['file_name']] == '.mp3')
                    $img = "<audio src='".$file['file_url']."' controls></audio>";
                else if($formats[$file['file_name']] == '.mp4')
                    $img = "<video src='".$file['file_url']."' controls></video>";
    
                echo "<div class='files'>";
                echo "<span><img src='web/img/user.png' alt='user'/>&nbsp;&nbsp;".$table_usernames[$cpt2]."</span>";
                echo $img;
                echo "<form action='?action=profil' method='POST'>
                <div class='file_name'>".$file['file_name']."</div>
                <div class='icons'>    
                    <span><a href='".$file['file_url']."' download><img src='web/img/download.png' alt='download'/></a></span>
                </div>
                <input class = 'none' type='text' name='current_file_name' value='".$file['file_name']."'>
                <input class='none' type='text' name='file_to_rename' value='".$file['file_url']."'>
                <input class = 'rename_box' type='text' name='new_file_name' placeHolder='Nouveau nom du fichier'>
                <input class = 'rename_box' type='submit' name='submit_rename_file'>
            </form>";
            echo "</div>";
             $cpt2++;

        }
        
     ?>
 </div>
        </div>	
    </body>
</html>

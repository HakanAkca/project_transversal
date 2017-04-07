<?php

	require_once('model/profil.php');
	
	function profil_action()
	{
		$add_file_succes = ''; 
		$delete_file_succes = '';
		$add_folder_message = '';
		$folder = '';
		$rename = '';
		$replace = '';
		$move_file = '';

		if (!empty($_SESSION['user_id']))
	    {
		    if(add_file()){
		    	$add_file_succes = 'Fichier ajouté avec succés';
		    }
		    if(rename_file()){
		    	header('Location: ?action=profil');
		    	exit(0);
		    }
		    
		    $my_files = my_files();
		    $formats = array();
		    foreach($my_files as $file){
		    	$formats[$file['file_name']] = file_extension($file['file_name']);
		    }

		    if(delete_file()){
		    	header('Location: ?action=profil');
		    	exit(0);
		    }
		    $dir = "uploads/".$_SESSION['user_username'];
		    
		    if(add_folder()){
		    	$folder = 'Dossier créé avec succes';
		    }

		    $allmydirectory = dirToArray($dir);

		    if(replace_file()){
		    	header('Location: ?action=profil');
		    	exit(0);
		    }
		    if(modif_file_txt()){
		    	header('Location: ?action=profil');
		    	exit(0);
		    }

		    if(move_file()){
		    	$move_file = 'move OK';
		    	header('Location: ?action=profil');
		    	exit(0);
		    }
		    if(rename_folder()){
		    	header('Location: ?action=profil');
		    	exit(0);
		    }
		    if(delete_folder()){
		    	header('Location: ?action=profil');
		    	exit(0);
		    }
		    require_once('views/profil.php');
	    }else{
	    	header('Location: ?action=login');
	        exit(0);
	    }  
	}
?>
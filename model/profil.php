<?php

	require_once('model/db.php');
	
	function add_file(){
		$bool = false;
		if(isset($_POST['sumbit_add_file'])){
			if(isset($_FILES['add_file']['name']) && !empty($_FILES)){
				$file_tmp_name = $_FILES['add_file']['tmp_name'];
				$directory = $_POST['directory'];
				$default_choice = 'ajouter_dans_un_dossier';
				if($_POST['nickname'] == ''){
					$file['file_name'] = $_FILES['add_file']['name'];
					if($directory != $default_choice){
						$file['url'] = 'uploads/'.$_SESSION['user_username'] . '/' . $directory .'/' . $file["file_name"];
					}else{
						$file['url'] = 'uploads/'.$_SESSION['user_username'] . '/' . $file["file_name"];
					}
	 				$file['id_user'] = $_SESSION['user_id'];
	 				$file['date'] = getDatetimeNow();

	 				if(extension_accept($file['file_name'])){
	 					if(!file_exist($file['url'])){
			 				db_insert('files', $file);
			 				move_uploaded_file($file_tmp_name, $file['url']);
			 				$bool = true;
	 					}
	 				}	
				}else{
					$name_die = $_FILES['add_file']['name'];
					$extension = file_extension($name_die);
					$file['file_name'] = $_POST['nickname'].$extension;
					if($directory != $default_choice){
						$file['url'] = 'uploads/'.$_SESSION['user_username'] . '/' . $directory .'/' . $file["file_name"];
					}else{
						$file['url'] = 'uploads/'.$_SESSION['user_username'] . '/' . $file["file_name"];
					}
	 				$file['id_user'] = $_SESSION['user_id'];
	 				$file['date'] = getDatetimeNow();
	 				if(extension_accept($file['file_name'])){
	 					if(!file_exist($file['url'])){
		 					db_insert('files', $file);
		 					move_uploaded_file($file_tmp_name, $file['url']);
		 					$bool = true;
		 					$date = give_me_date(); 
    						$actions = $date . ' -- ' .$_SESSION['user_username'] . ' add file : '. $file['url'] ."\n"; 
    						watch_action_log('access.log',$actions);
	 					}
	 				}
				}
			}
		}
		return $bool;
	}

	function get_file_by_file_url($file_url)
	{
		$id_user = $_SESSION['user_id'];
    	$data = find_one_secure("SELECT * FROM files WHERE file_url = :file_url AND 
    								id_user = :id_user",
                            ['file_url' => $file_url,
                             'id_user' => $id_user]);
    	return $data;
	}
	function get_file_by_file_name($file_name)
	{
		$id_user = $_SESSION['user_id'];
    	$data = find_one_secure("SELECT * FROM files WHERE file_name = :file_name AND 
    								id_user = :id_user",
                            ['file_name' => $file_name,
                             'id_user' => $id_user]);
    	return $data;
	}

	function file_exist($url){
		$data = get_file_by_file_url($url);
    	if ($data == false){
        	return false;
    	}
    	return true;
	}

	function file_exist_by_name($name){
		$data = get_file_by_file_name($name);
    	if ($data == false){
        	return false;
    	}
    	return true;
	}

	function my_files(){
		$id_user = $_SESSION['user_id'];
 
		$data = find_all_secure("SELECT * FROM files WHERE id_user = :id_user ORDER BY DATE DESC",
                            ['id_user' => $id_user]);
		return $data;
	}

	function file_extension($file_name){
		return strrchr($file_name, '.');
	}

	function extension_accept($file_name){
		$extension_autorisees = array('.jpg', '.jpeg', '.txt','.png','.pdf', '.mp3', '.mp4');
		$format = file_extension($file_name);
		return in_array($format, $extension_autorisees);
	}

	function getDatetimeNow() {
	    $tz_object = new DateTimeZone('Europe/Paris');
	    date_default_timezone_set('Europe/Paris');
	    $datetime = new DateTime();
	    $datetime->setTimezone($tz_object);
	    return $datetime->format('Y\-m\-d\ h:i:s');
	}

	function delete_file(){
		$bool = false;
		if(isset($_POST['submit_delete_file'])){
			if($_POST['file_to_delete'] != ''){
				$id_user = $_SESSION['user_id'];
				$file_url = $_POST['file_to_delete'];
				if(!find_one_secure("DELETE FROM files WHERE file_url = :file_url AND id_user = :id_user",
                            ['file_url' => $file_url,
                             'id_user' => $id_user])){
					unlink($file_url);
					$bool = true;
					$date = give_me_date(); 
    				$actions = $date . ' -- ' .$_SESSION['user_username'] . ' delete file : '. $file_url ."\n"; 
    				watch_action_log('access.log',$actions);
				}
			}
		}
		return $bool;
	}

	function rename_file(){
		$bool = false;
		if(isset($_POST['submit_rename_file'])){
			if($_POST['current_file_name'] != '' && $_POST['file_to_rename'] != '' && $_POST['new_file_name'] != ''){
				$id_user = $_SESSION['user_id'];
				$current_file_name = $_POST['current_file_name'];
				$current_file_url = $_POST['file_to_rename'];
				$file_extension = file_extension($current_file_name);
				$file_name = $_POST['new_file_name'].$file_extension;
				$file_url = substr($current_file_url, 0, -(strlen($current_file_name))).$file_name;
				if(!file_exist($file_url)){
					if(!find_one_secure("UPDATE files SET file_name = :file_name , file_url = :file_url  WHERE id_user = :id_user AND file_url = :current_file_url",
                            ['file_name' => $file_name,
                             'file_url' => $file_url,
                             'current_file_url' => $current_file_url,
                             'id_user' => $id_user])){
						rename($current_file_url, $file_url);
						$bool = true;
					}
				}
			}
		}
		return $bool;
	}

	function replace_file(){
		$bool = false;
		if(isset($_POST['submit_replace_file'])){
			if(isset($_FILES['file']['name']) && !empty($_FILES) 
				&& $_POST['file_name_to_replace'] !== '' && 
				$_POST['directory_replace'] !== ''){
				$id_user = $_SESSION['user_id'];
				$file_tmp_name = $_FILES['file']['tmp_name'];
				$file_name_to_replace = $_POST['file_name_to_replace'];
				$new_file_name = $_FILES['file']['name'];
				$directory = $_POST['directory_replace'];
				$default_choice = 'Racine';
				if($directory == $default_choice){
					if(file_exist_by_name($file_name_to_replace) && 
					$new_file_name !== '' ){
						if(!file_exist_by_name($new_file_name)){
							$data = get_file_by_file_name($file_name_to_replace);
							$file_url = $data['file_url'];
							$new_file_url = substr($file_url, 0, -(strlen($file_name_to_replace))).$new_file_name;
							echo $new_file_url;
							if(!find_one_secure("UPDATE files SET file_name = :new_file_name , file_url = :new_file_url  WHERE id_user = :id_user AND file_name = :file_name_to_replace",
	                            ['new_file_name' => $new_file_name,
	                             'new_file_url' => $new_file_url,
	                             'file_name_to_replace' => $file_name_to_replace,
	                             'id_user' => $id_user])){
								move_uploaded_file($file_tmp_name,$new_file_url);
	                            unlink($file_url);
	                            $bool = true;
							}
						}
					}
				}else{
					$data = get_file_by_file_name($file_name_to_replace);
					$file_url = $data['file_url'];
					$new_file_url = substr($file_url, 0, -(strlen($file_name_to_replace))).$new_file_name;
					echo 'old ' .$file_url.'<br>';
					echo 'new url ' .$new_file_url;
					if(!file_exist($new_file_url)){
						if(!find_one_secure("UPDATE files SET file_name = :new_file_name , file_url = :new_file_url  WHERE id_user = :id_user AND file_url = :file_url",
	                            ['new_file_name' => $new_file_name,
	                             'new_file_url' => $new_file_url,
	                             'file_url' => $file_url,
	                             'id_user' => $id_user])){
								echo 'old' .$file_url.'<br>';
								echo $new_file_url;
								move_uploaded_file($file_tmp_name,$new_file_url);
	                            unlink($file_url);
	                            $bool = true;
							}
					}
				}
			}
		}
		return $bool;
	}


	function add_folder(){
		$bool = false;
		if(isset($_POST['sumbit_add_folder'])){
			if(isset($_POST['new_folder_name']) AND $_POST['new_folder_name'] !== ''){
				$name_folder = $_POST['new_folder_name'];
				mkdir('uploads/'.$_SESSION['user_username'].'/'.$name_folder);
				$bool = true;
			}
		}

		return $bool;
	}

	function dirToArray($dir) {
		$result = array();
		$cdir = scandir($dir);
		foreach ($cdir as $key => $value)
		{
			if (!in_array($value,array(".","..")))
			{
				if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
				{
				    $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
		        }
				else
				{
				    $result[] = $value;
				}
			}
		}
		return $result;
	}
		    	
	function modif_file_txt(){
		$bool = false;
		if(isset($_POST['modif_txt'])){
			if(isset($_POST['txt_content']) && isset($_POST['url_txt']) && $_POST['url_txt'] != ''){
				$file = $_POST['url_txt'];
				$txt_content = $_POST['txt_content'];
				file_put_contents($file, $txt_content);
				$bool = true;
			}
		}
		return $bool;
	}
	
	function move_file(){
		$bool = false;
		if(isset($_POST['submit_move_file'])){
			if(isset($_POST['url_file_to_move']) && 
				$_POST['url_file_to_move'] != '' &&
				isset($_POST['name_file_to_move']) && 
				$_POST['name_file_to_move'] != '' &&
				$_POST['directory_move'] != ''){
					$id_user = $_SESSION['user_id'];
					$directory = $_POST['directory_move'];
					$default_choice = 'Racine';
					$racine = "uploads/".$_SESSION['user_username']."/";
					$file_name = $_POST['name_file_to_move'];
					$old_url = $_POST['url_file_to_move'];
					if($directory !== $default_choice){
						$racine = "uploads/".$_SESSION['user_username']."/".$directory."/";
						$new_file_url = $racine.$file_name;
						if(!find_one_secure("UPDATE files SET file_url = :new_file_url  WHERE id_user = :id_user AND file_url = :old_url",
                            ['new_file_url' => $new_file_url,
                             'old_url' => $old_url,
                             'id_user' => $id_user])){
							rename($old_url, $new_file_url);
							$bool = true;
						}
					}else{
						$new_racine = substr($old_url, 0, strlen($racine));
						$new_file_url = $new_racine.$file_name;
						
						if(!find_one_secure("UPDATE files SET file_url = :new_file_url  WHERE id_user = :id_user AND file_url = :old_url",
                            ['new_file_url' => $new_file_url,
                             'old_url' => $old_url,
                             'id_user' => $id_user])){
							rename($old_url, $new_file_url);
							$bool = true;
						}
					}
				}
		
		}
		return $bool;
	}

	function rename_folder(){
		$bool = false;
		if(isset($_POST['submit_rename_folder'])){
			if(isset($_POST['new_folder_name']) && $_POST['new_folder_name'] != '' &&
				isset($_POST['old_folder_name']) && $_POST['old_folder_name'] != ''){
				$id_user = $_SESSION['user_id'];
				$dir = 'uploads/'.$_SESSION['user_username'];
				$all_dir = dirToArray($dir);
				$old_folder_name = $_POST['old_folder_name'];
				$new_folder_name = $_POST['new_folder_name'];
				foreach ($all_dir as $key => $value) {
					if($key == $old_folder_name){
						if(is_array($value)){
							if(!empty($value)){
								foreach ($value as $key2 => $value2) {
									$file_url = $dir.'/'.$key.'/'.$value2;
									$new_file_url = $dir.'/'.$new_folder_name.'/'.$value2;
									if(!find_one_secure("UPDATE files SET file_url = :new_file_url  WHERE id_user = :id_user AND file_url = :file_url",
	                            	['new_file_url' => $new_file_url,
	                             	'file_url' => $file_url,
	                             	'id_user' => $id_user])){
										rename($dir.'/'.$key, $dir.'/'.$new_folder_name);
										$bool = true;
									}
								}
							
							}else{
								rename($dir.'/'.$key, $dir.'/'.$new_folder_name);
							}
						}
					}
				}
			}
		}
		return $bool;
	}

	function delete_folder(){
		$bool = false;
		if(isset($_POST['submit_delete_folder'])){
			if(isset($_POST['name_folder_to_delete']) && $_POST['name_folder_to_delete'] != ''){
				$name_folder_to_delete = $_POST['name_folder_to_delete'];
				$dir = 'uploads/'.$_SESSION['user_username'].'/'.$name_folder_to_delete;
				$all_folders = dirToArray($dir);
				$id_user = $_SESSION['user_id'];
				foreach ($all_folders as $key => $value) {
					if(isset($value)){
						$url = $dir.'/'.$value;
						if(!find_one_secure("DELETE FROM files WHERE file_url = :url AND id_user = :id_user",
                            ['url' => $url,
                             'id_user' => $id_user])){
							unlink($url);
							$bool = true;
						}	
					}
				}
				rmdir($dir);
			}
		}
		return $bool;
	}

	/*function zipMyFolder(){

    if(isset($_POST['downloadDirectory'])){
    // Get real path for our folder
$rootPath = $_POST["directoryToDownload"];

// Initialize archive object
$zip = new ZipArchive();
$archive_file_name = basename($_POST["directoryToDownload"]).'.zip';
$zip->open($_POST["directoryToDownload"].'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{

    if (!$file->isDir())
    {

        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}

$zip->close();
$user = get_user_by_id($_SESSION['user_id']);
$username = $user["lastname"];
header("Content-type: application/zip"); 
header("Content-Disposition: attachment; filename=$archive_file_name");
header("Content-length: " . filesize("uploads/".$username.'/'.$archive_file_name));
header("Pragma: no-cache"); 
header("Expires: 0"); 
readfile("uploads/"."$username"."/"."$archive_file_name");
    }
}

            <form action='?action=profil' method='post' class='formDirectory' name='formDownloadDirectory'><label for='downloadMyDir'>
            <img class='imgDirect' src='assets/img/downloadfolder.png'></label>
            <input type='text' name='directoryToDownload' value=".realpath($allmydir)." class='noneInputText'>
            <input type='submit' name='downloadDirectory' value=".realpath($allmydir)." class='noneMyDelete' id='downloadMyDir'>
            </form>*/


?>
<?php

require_once('model/user.php');


function home_action()
{
    if (!empty($_SESSION['user_id']))
    {
        $username = $_SESSION['user_username'];
        $all_files = all_files();
        $formats = array();
        $table_usernames = array();
        $cpt = 0;
        foreach($all_files as $file){
            $data = get_user_by_id($file['id_user']);
            $table_usernames[$cpt] = $data['username'];
            $cpt++;
            $formats[$file['file_name']] = file_extension($file['file_name']);
        }
        require('views/header.php');
        require('views/home.php');
    }
    else {
        header('Location: ?action=login');
        exit(0);
    }
}

function about_action()
{
    require('views/about.html');
}

function contact_action()
{
    require('views/contact.html');
}

function profil_action()
{
    require('views/profil.php');
}

<?php

    session_start();

    $link = mysqli_connect("", "", "", "");
    
    if(mysqli_connect_errno()){
        
        print_r(mysqli_connect_error());
        die();
        
    }

    if($_GET['function'] == 'logout'){
        session_unset();
    }
    

?>
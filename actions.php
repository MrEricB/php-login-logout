<?php
// where forms, _POST, _GET are submitted


    include("functions.php");

    
    if($_GET['action'] == "loginSignup"){
        
        //validate email and password ie make sure fields are filled in
        $error = "";
        if(!$_POST['email']){
            
            $error = "An email address is required";
            
        } else if (!$_POST['password']){

            $error = "A password is required";
            
        } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            
            $error = "A valid email address is required";
            
        }
        // if there is an error then alert it
        if($error != ""){
            echo $error;
            die();
        }
  
       if ($_POST['loginActive'] == "0") { // try to sign user up
            
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
                $error = "That email address is already taken.";  
            } else {
                $query = "INSERT INTO users (`email`,`password`) VALUES ('". mysqli_real_escape_string($link, $_POST['email'])."','". mysqli_real_escape_string($link, $_POST['password'])."')";
                
                if(mysqli_query($link, $query)){
                    
                    // seesion[i] needs to be here insead of after mysqli_qeury or else it will allways be 0
                    $_SESSION['id'] = mysqli_insert_id($link);
                    
                    $query = "UPDATE users SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
                    
                    mysqli_query($link, $query);
                  
                    echo 1;
                   
                    
                } else {
                    $error = "Couldn't create user, please try again later";
                }
                
            }
           
            
            
        } else { // try and log the user in
           
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
           
           $result = mysqli_query($link, $query);
           
           $row = mysqli_fetch_assoc($result); 
               
           if($row['password'] == md5(md5($row['id']).$_POST['password'])) {
               
               echo 1;
               
               $_SESSION['id'] = $row['id'];
               
           } else {
               $error = "Could not find that username/password";
           }
           
       }
        
        if ($error != "") {
            
            echo $error;
            exit();
            
        }
        
    }

?>
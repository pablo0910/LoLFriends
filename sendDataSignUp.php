<?php

/*
 *
 * Done by Pablo Jimenez - pablo0910@outlook.es
 *
 */

    require 'connectToDB.php';

    //$to_email       = "djcolegax@gmail.com"; //Recipient email, Replace with own email here
    
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        
        $output = json_encode(array( //create JSON data
            'type'=>'error', 
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output); //exit script outputting json data
    } 
    
    //Sanitize input data using PHP filter_var().
    $nick      = filter_var($_POST["nick"], FILTER_SANITIZE_STRING);
    $email     = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password  = md5(filter_var($_POST["password"], FILTER_SANITIZE_STRING));

    $subject = "Hola";

    
    //additional php validation
    if(strlen($nick)<4){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //email validation
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }
    
    //email body
    //$message_body = "\r\n\r\n-".$nick."\r\nEmail : ".$email."\r\nPassword : (".$password.") " ;
    
    //proceed with PHP email.
    /*$headers = 'From: '.$nick.'' . "\r\n" .
    'Reply-To: '.$email.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();*/
    
    //$send_mail = mail($email, $subject, $message_body, $headers);
    $sql = "INSERT INTO user (nick, password, email) VALUES ('$nick', '$password', '$email')";

    if ($conn->query($sql) === TRUE) {

        echo "New record created successfully";

    } else {
        echo "Error: " . $sql . " - " . $conn->error;
    }
    
    /*if(!$send_mail)
    {
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$nick .' Thank you for your email'));
        die($output);
    }*/
?>

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

    session_start();
    
    //Sanitize input data using PHP filter_var().
    $nick      = filter_var($_POST["nick"], FILTER_SANITIZE_STRING);
    $password  = md5(filter_var($_POST["password"], FILTER_SANITIZE_STRING));
    //email body
    //$message_body = "\r\n\r\n-".$nick."\r\nEmail : ".$email."\r\nPassword : (".$password.") " ;
    
    //proceed with PHP email.
    /*$headers = 'From: '.$nick.'' . "\r\n" .
    'Reply-To: '.$email.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();*/
    
    //$send_mail = mail($email, $subject, $message_body, $headers);
    $sql = "SELECT * FROM user WHERE nick = '$nick' AND password = '$password'";

    if ($result=mysqli_query($conn,$sql)) {

        // Fetch one and one row
        if ($row=mysqli_fetch_row($result)) {

            $timeCookie = time() * time();
            setcookie('lolfriendscookie', $timeCookie, time() + 365 * 24 * 60 * 60);

            $_SESSION['lolfriendssession'] = true;
            $_SESSION['lolfriendsname'] = $row[1];
            $_SESSION['lolfriendssummname'] = $row[4];

            $sql = "UPDATE user SET cookie = '$timeCookie' WHERE id = '$row[0]'";
            if ($conn->query($sql) === TRUE) {

                echo "true";

            } else {

                echo "Error: " . $sql . " - " . $conn->error;

            }

        }
        // Free result set
        mysqli_free_result($result);
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

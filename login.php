<?php
include 'core/init.php';
    
if(empty($_POST)===FALSE){
    $username= $_POST['username'];
    $password = $_POST['password'];
        
    if(empty($username) || empty($password)) {
        $errors[] = 'you need to enter a username and password';
    } elseif (user_exists($username) === false) {
        $errors[] = 'We can\t find your name have you registered?';
    } elseif (user_active($username) === false) {
        $errors[] = 'You haven\'t activated your account!';
    } else {
        
        if(strlen($password) > 32){
            $errors[] = 'Password too long';
        }
        
        $login = login($username, $password); // login($username, $password) returns user_id if successful.
        if ($login === false) {
            $errors[] = 'That  username/password combination is incorrect';
        } else {
            $_SESSION['user_id'] = $login;  // $login holds `user_id` from database
            redirect('index.php');
            exit();
        }
    }
    
} else {
    $errors[] = 'No data received';
}
/*
 * All the above codes are the logics behind the logging in the user and below are the markup and  error handelling
 */
include 'includes/overall/header.php';
/*
 * Check if there are any errors in the loggin in process
 * if there are any errors all the errors have been collected in the $errors array
 * they are checked in the if clause and then displayed with the help of output_errors() function which
 * displays the errors in a nice way.
 */
if (empty($errors) === false){
    echo "<h2>We tried to log you in, but...</h2>";
    echo output_errors($errors);
}
 
include 'includes/overall/footer.php';


<?php
ob_start();
session_start();
ini_set('display_errors', 'on');
//error_reporting(E_ALL|E_STRICT);

require 'database/connect.php';
require 'functions/general.php';
require 'functions/users.php';

/*
 * Explanation of the code below.
 * This logged_in() function checks if the session is set or not. 
 * if the session is set we pass the user details in the function call user_data() function.
 * now the funcion user_data() in functions/users.php file have access to all the user details 
 * passed to function call user_data().Thus, the variable $user_data is an assocaitive 
 * array of keys (passed in the user_data() function and value (retrieved from the database) pair.
 *  
 */
if(logged_in() === TRUE){  
    $session_user_id = $_SESSION['user_id'];
    $user_data = user_data($session_user_id, 'user_id', 'username', 'password', 'first_name', 'last_name', 'email', 'email_code');
    /*
     * the if clause below checks if the user is active or not. if the user is not active by any reason 
     * this logic will log the user out and will restrict the user from browsing the user web pages.
     */
    if(user_active($user_data['username']) === false){
        session_destroy();
        redirect('index.php');
        exit();
    }
    
}
$errors = array();

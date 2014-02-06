<?php
/*
 * input@ associative array passed from registraion page.
 * @return: it inserts the data passed in the registraion form into the database lr.users
 */
function register_user($register_data){
    array_walk($register_data, 'array_sanitize');
    $register_data['password'] = md5($register_data['password']);
    
    $fields = '`' . implode('`, `', array_keys($register_data)) . '`';
    $data = '\'' . implode('\', \'', $register_data) . '\'';
 
    mysql_query("INSERT INTO `users` ($fields) VALUES ($data)");
}
/*
 * input@ none
 * @return : total number of active users in the the table 'lr.users'.
 */
function user_count(){
    return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = 1"), 0);  
}
/*
 * input@ user details
 * @return: associative array of keys (passed in the user_data() 
 *          function and value (retrieved from the database) pair.
 * Though the function user_data() seems to accept only one argument,
 * it can accept any numner of parameters paseed in this function. 
 * the php library function func_num_args() returns the number of 
 * parameters passed from the funcion call whereas func_get_args()
 * returns the actual parameters passed in the funcion call.
 * in the if caluse, it is checked whether the number of parameters passed are 
 * greater than 1 or not. if it is greater than 1 then, the arguments passed in the funcion call user_data() 
 * is more than only the session user id. 
 * Here in the function defination user_data() function the the argument $user_id is the first parameter passed 
 * in the funcion call. if there were two arguments in the funcion defination, 
 * for instance funcion user_data($user_id, $temp) $temp would be the second parameter passed the 
 * funcion call user_data() fucnion.
 * 
 */
function user_data($user_id){
    $data = array();
    $user_id = (int)$user_id;    
    $func_num_args =  func_num_args();
    $func_get_args = func_get_args();
    
    if($func_num_args >1){
        unset($func_get_args[0]);        
        $fields = '`' . implode('`, `', $func_get_args) . '`';        
        $data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `users` WHERE `user_id` = $user_id")); 
        return $data;
    }    
}


/*
 * input@ none
 * return @ true if session is set false otherwise
 */
function logged_in(){
    return (isset($_SESSION['user_id'])) ? true : false;
}

/*
 * input email
 * @return: true if the email exists in the databse else false
 */
 function email_exists($email){
    $email = sanitize($email);
    $query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email'");
    return (mysql_result($query, 0)==1) ? true : false;
 }

/*
 * input username
 * @return: true if the username exists in the databse else false
 */
 function user_exists($username){
    $username = sanitize($username);
    $query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username'");
    return (mysql_result($query, 0)==1) ? true : false;
 }
 /*
  * input @ username
  * @return: true if the active field associated with the username in the database is 1 else false
  */
 function user_active($username){
     $username = sanitize($username);
     $query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `active`= 1");
     return (mysql_result($query, 0)==1) ? true : false;
 }
 /*
  * input@ username
  * @return: user_id
  */
 function user_id_from_username($username){
     $username = sanitize($username);
     $query = mysql_query("SELECT `user_id` FROM `users` WHERE `username` = '$username'");
     return mysql_result($query, 0, 'user_id');        
 }
 
 /*
  * input @ username and password
  * @return: return user_id from the database if the username and password combination matches
  */
 function login($username, $password){
     $username = sanitize($username);
     $password = md5($password);
     $user_id = user_id_from_username($username);
     $query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `password` = '$password'");
     return (mysql_result($query, 0)==1) ? $user_id : false ;
 }
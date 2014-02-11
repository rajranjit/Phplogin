<?php
    /*
     * input@ mode(username or password) and email address
     * user data is retrived from the user_data function by passing the user id
     * check whether the mode is username or password
     * if the mode is uesrname send the user's username to their email address
     * if password: generate a temporary password
     * change the password to temporary password
     * then update the password recovery flag to 1 indicatin password needs to be changed
     * send user the new temporary password.
     */
    function recover($mode, $email){
        $mode = sanitize($mode);
        $email = sanitize($email);        
        $user_data = user_data(user_id_from_email($email), 'user_id', 'first_name', 'username');
        
        if($mode == 'username'){
            //
            email($email, 'Your username', 
                    "Hello " . $user_data['first_name'] . ", \n\nYour username is: " . $user_data['username'] . "\n\n-rajranjit@tutorial");
        } elseif ($mode == 'password') {
            $generated_password = substr(md5(rand(999, 999999)), 0, 5);
            change_password($user_data['user_id'], $generated_password);
            update_user($user_data['user_id'], array('password_recover' => 1));
            
            email($email, 'Your password recovery', 
                    "Hello " . $user_data['first_name'] . ", \n\nYour temporary new password is: " . $generated_password . "\n\n-rajranjit@tutorial");
            
        }
    }
    /*
     * input@ user_id
     * @return: ture if the passed user_id has type equal to 1 false otherwise.
     */
    function is_admin($user_id){
        $user_id = (int)$user_id;
        return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = $user_id AND `type` = 1"), 0) == 1) ? true : false;
    }
    /*
    * input@ email
    * @return: user_id
    */
    function user_id_from_email($email){
        $email = sanitize($email);
        $query = mysql_query("SELECT `user_id` FROM `users` WHERE `email` = '$email'");
        return mysql_result($query, 0, 'user_id'); 
    }
    /*
     * input@ data sent by form in the form of associative array 
     * return@ SQLQuery update
     */
    function update_user($user_id, $update_data){
        array_walk($update_data, 'array_sanitize');
        foreach ($update_data as $fields => $data) {
           $update[] = '`' . $fields . '` = \'' . $data . '\''; 
        }
        mysql_query("UPDATE `users` SET " . implode(', ', $update) . " WHERE `user_id` = $user_id");
    }
    /*
     * input@ email address and the email activation(email_code) code
     * @return: true if the email and email activation (email_code) code and active state = 0 all matches in the database.
     *          else false 
     */
    function activate($email, $email_code){
        $email = mysql_real_escape_string($email);
        $email_code = mysql_real_escape_string($email_code);
        if(mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email' AND `email_code` = '$email_code' AND `active` = 0"), 0) == 1){
            mysql_query("UPDATE `users` SET `active` = 1 WHERE `email` = '$email'");
            return true;
        } else{
            return false;
        }     
    }
    /*
     * input@ user_id and new password
     * @return: none, it makes a mysql query to update the passsword field.
     */
function change_password($user_id, $password){
    $user_id = (int)$user_id;
    $password = md5($password);
    mysql_query("UPDATE `users` SET `password` = '$password', `password_recover` = 0 WHERE `user_id` = $user_id");
}
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
    mail($register_data['email'], 'Activate your account', "Hello " . $register_data['first_name'] . ","
            . "\n\nYou need to activate your account, so use the link below:"
            . "\n\nhttp://localhost/Phplogin/activate.php?"
            . "email=" . $register_data['email'] . "&email_code=" . $register_data['email_code'] . "\n\n-raj@ranjit");
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
<?php
    /*
     * input@ receivers email address, subject of the email, and the message of the email/body of the email
     * @return: none, it send the email to $to receiver.
     */

    function email($to, $subject, $message){
        mail($to, $subject, $message, "From: raj@tutorialmail.com");
    }
    /*
     * input@ none
     * @return: none
     * This function redirects logged in user to index.php
     */
    function logged_in_redirect(){
        if(logged_in() === true){
            redirect('index.php');
            exit();
        }
    }
    
    
    /*
     * input@ none
     * @return: none
     * this function redirects non admin users to the home page(idnex page).
     */
    function admin_protect(){
        global $user_data;
        if(is_admin($user_data['user_id']) == false){
            redirect('index.php');
            exit();
        }
    }

    /*
     * input@ none
     * @return: none
     * this function redirects non logged in users to the generic message page (protected.php)
     */
    function protect_page(){
        if(logged_in() === false){
            redirect('protected.php');
            exit();
        }
    }
    /*
     * input@ data but no the value but the reference
     * @return: mysql escaped variable and then stripped tags and passed to htmlentities
     */
    function array_sanitize(&$item){
        $item = htmlentities(strip_tags(mysql_real_escape_string($item)));
    }
    /*
     * input@ input variable from the user
     * @ return: mysql escaped variable and then stripped tags and passed to htmlentities
     */
    function sanitize($data){
        return htmlentities(strip_tags(mysql_real_escape_string($data)));
    }
    
    /*
     * intput @ webpage name
     * @return: redirect to inputed location 
     */
    function redirect($path){
        return header("Location: $path");
    }
    
    function output_errors($errors){
        return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
    }
    

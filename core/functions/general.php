<?php
    /*
     * input@ input variable from the user
     * @ return: mysql escaped variable
     */
    function sanitize($data){
        return mysql_real_escape_string($data);
    }
    
    /*
     * intput @ webpage name
     * @return: redirect to inputed location 
     */
    function redirect($path){
        return header('Location:' . $path);
    }
    
    function output_errors($errors){
        return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
    }
    

<?php
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/header.php';

if(empty($_POST) === false){
    $required_fields = array('username', 'password', 'password_again', 'first_name', 'email');
    foreach ($_POST as $key=>$value) {
        if(empty($value) && in_array($key, $required_fields)){
            $errors[] = 'Fields marked with an asterisk are required';
            break 1;//if any one of the required fields are missing then break out of the look.
        }
    }
    
    if (empty($errors) === true){
        if(user_exists($_POST['username']) === true){
            $errors[] = 'Sorry, the username \'' . $_POST['username'] . '\' is already taken';
        }
        if(preg_match("/\\s/", $_POST['username']) == true){
            $errors[] = 'Your username must not contain any spaces';
        }
        if(strlen(trim($_POST['password'])) < 6){
            $errors[] = 'Your password must be atleast 6 characters';
        }
        if(trim($_POST['password']) !== trim($_POST['password_again'])){
            $errors[] = 'Your passwords do not match';            
        }
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
            $errors[] = 'A valid email address is required';
        }
        if(email_exists($_POST['email']) === true){
            $errors[] = 'Sorry, the email \'' . $_POST['email'] . '\' is already in use';
        }
    }
}

?>            
<h1>Register</h1>

<?php

if(isset($_GET['success']) && empty($_GET['success'])){
    echo 'You\'ve been registered successfully! Please check your email to activate your account.';
} else {
    if(empty($_POST) === false && empty($errors) === true){
        $register_data = array(
            'username'      => $_POST['username'],
            'password'      => $_POST['password'],
            'first_name'    => $_POST['first_name'],
            'last_name'     => $_POST['last_name'],
            'email'         => $_POST['email'],
            'email_code'    => md5($_POST['username'] + microtime())
        );

        register_user($register_data);
        header('Location: register.php?success');
        exit();

    } elseif(empty ($errors) === false) {
        echo output_errors($errors);
    }

    ?>



    <form action="" method="post">
        <ul>
            <li>
                Username*:<br>
                <input type="text" name="username" value="">
            </li>
            <li>
                Password*:<br>
                <input type="password" name="password">
            </li>
            <li>
                Password again*:<br>
                <input type="password" name="password_again">
            </li>
            <li>
                First name*:<br>
                <input type="text" name="first_name" value="">
            </li>
            <li>
                Last name:<br>
                <input type="text" name="last_name" value="">
            </li>
            <li>
                Email*:<br>
                <input type="text" name="email" value="">
            </li>
            <li>
                <input type="submit" value="Register">
            </li>

        </ul>

    </form>


    <?php 
}
include 'includes/overall/footer.php'; ?>

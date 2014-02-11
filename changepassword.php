<?php 
include 'core/init.php';
protect_page();

if(empty($_POST) === false){//if data have been posted on the form/ form is not empty
    $required_fields = array('current_password', 'password', 'password_again');  //variables in $required_fields are all the required fields in the form
    foreach ($_POST as $key => $value) {
        if(empty($value) === true && in_array($key, $required_fields)){  //if the value in the form is emply and the key is in the requied_fields array then there is a problem.
            $errors[] = 'Fields marked with an asterisk are required';
            break 1;//if there is one error then break out of the foreach loop.
        }
    }
    
    if(md5(trim($_POST['current_password'])) === $user_data['password']){// check if the current password matches
        if(trim($_POST['password']) !== trim($_POST['password_again'])){
            $errors[] = 'Your new passwords do not match.';
        } elseif(strlen(trim($_POST['password'])) < 6){
            $errors[] = 'Your password must be atleast 6 characters';
        }
    } else {
        $errors[] = 'Your current password is not correct.';
    }
    print_r($errors);
}
include 'includes/overall/header.php';   
?>  

<h1>Change Password</h1>
<?php
if(isset($_GET['success']) === true && empty($_GET['success']) === true){
    echo 'Your password has been changed.';
} else {
    if(isset($_GET['force']) === true && empty($_GET['force']) === true){
        echo "<p>You must change your password before you go the other pages.</p>"; 
    }

    if(empty($_POST) === false && empty($errors) === true){
        //change password
        change_password($session_user_id, trim($_POST['password']));
        header('Location: changepassword.php?success');
        exit();

    } elseif (empty ($errors) === false) {
        //output errors
        echo output_errors($errors);
    }
?>

    <form action ="" method="post">
        <ul>
            <li>
                Current Password*:<br>
                <input type="password" name="current_password">
            </li>
            <li>
                New Password*:<br>
                <input type="password" name="password">
            </li>
            <li>
                New password Again*:<br>
                <input type="password" name="password_again">
            </li>
            <li>
                <input type="submit" value="Change Password">
            </li>
        </ul>
    </form>

<?php 
}
include 'includes/overall/footer.php'; 


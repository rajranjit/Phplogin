<?php 
include 'core/init.php';
include 'includes/overall/header.php';   
?>  

<h1>Home</h1>
<p>Just a template.</p>
<p>Finished 39</p>
<?php
if(is_admin($session_user_id)){
    echo "You are admin!<br>";
    echo '<a href="mail.php">Email all users.</a>';
}
?>

<?php include 'includes/overall/footer.php'; 

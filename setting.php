<?php 
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';   

if(empty($_POST) === false){
    $required_fields = array('', '')
}  else {
    

    ?>  

    <h1>Settings</h1>



    <form action="" method="post">

        <ul>
            <li>
                First name*:<br>
                <input type="text" name="first_name" value="<?php echo $user_data['first_name'];?>">
            </li>
            <li>
                Last name:<br>
                <input type="text" name="last_name" value="<?php echo $user_data['last_name'];?>">
            </li>
            <li>
                Email *:<br>
                <input type="text" name="email" value="<?php echo $user_data['email'];?>">
            </li>
            <li>
                <input type="submit" vlaue = "Update">
            </li>
        </ul>
    </form>


<?php 
}
include 'includes/overall/footer.php'; 

?>


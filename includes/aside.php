<aside>
    <?php
    /*
     * this if clause decides which page to dispaly depending upon whether the user is logged in or not
     */
    if (logged_in()){
       include 'includes/widgets/loggedin.php';
    } else {    
        include 'includes/widgets/login.php';
    }
    include 'includes/widgets/usercount.php';
    ?>
</aside>
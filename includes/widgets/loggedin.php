<div class ="widget">
    <h2> Hello, <?php echo $user_data['first_name'];?> !</h2>
    <div class ="inner">
        <ul>
            <li>
                <a href ="logout.php">Log out</a>
            </li>
            <li>
                <a href ="<?php echo $user_data['username'];?>">Profile</a>
            </li>
            <li>
                <a href ="changepassword.php">Change Password</a>
            </li>
            <li>
                <a href ="setting.php">Setting</a>
            </li>
        </ul>
    </div>
</div>


<div class="widget">
    <h2>Log in/ Register</h2>
    <div class="innter">
        <form action="login.php" method="post">
            <ul id="login">
                <li>
                    Username:<br>
                    <input tpye="text" name="username">
                </li> 
                <li>
                    Password:<br>
                    <input type="password" name="password">
                </li>
                <li>
                    <input type="submit" value="Log in">
                </li>
                <li>
                    Forgotten your <a href="recover.php?mode=username">username</a> 
                    or <a href="recover.php?mode=password">password</a>
                </li>
                <li>
                    <a href="register.php">Register</a>
                </li>
            </ul>
        </form>
    </div>
</div>
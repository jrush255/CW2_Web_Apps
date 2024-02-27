<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
</head>

<body>
<ul>
    <li><a class="active" href="../index.php">Home</a></li>
    <li style="float:right"><a href="../login/login_user.php">Log in</a></li>
    <li style="float:right"><a href="../registration/created_user.php">Register</a></li>
</ul>

<h3>You have now been logged out.</h3>

</body>
</html>

<?php
//See if there is a current session
session_start();
//Destroy session or "log out user"
session_destroy();

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body>
<ul>
    <li><a class="active" href="index.php">Home</a></li>
    <li><a href="admintest.php">TEST</a></li>
    <li style="float:right"><a href="login/login_user.php">Log in</a></li>
    <li style="float:right"><a href="registration/created_user.php">Register</a></li>
</ul>

<h1>Insert Homepage</h1>

</body>
</html>


<?php
//Restrict options based on user level (eg more options for admin, less for guest)
session_start();

if(isset($_SESSION["code"])){
    echo ("Welcome " . $_SESSION["user"]);

    echo("<br><br> What would you like to do today " . $_SESSION["user"] . "? <br>
        <a href='tasks/create_task.php'>1. Create Tasks</a><br>
        <a href='tasks/view_task.php'>2. View Tasks</a><br>
        <a href='tasks/edit_task.php'>3. Edit Tasks</a><br>
");

    echo ('<br><br><br><a href="logout/logout_user.php">Log Out</a>');

}

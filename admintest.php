<head>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<ul>
    <li><a class="active" href="index.php">Home</a></li>
    <li style="float:right"><a href="login/login_user.php">Log in</a></li>
    <li style="float:right"><a href="registration/created_user.php">Register</a></li>
</ul>



<?php

session_start();

if(isset($_SESSION["level"])){

    echo ("You have logged in successfully");
    echo $_SESSION["level"];

    if($_SESSION["level"] == "Admin")
    {
        echo ("You are an Admin " . $_SESSION["user"]);
    }
    else if($_SESSION["level"] == "User" or $_SESSION["level"] == "Guest")
    {
        echo("You do not have permission to register users " . $_SESSION["user"] . ". You are a " . $_SESSION["level"]);
    }
    else
    {
        echo("What have you done!? You broke it.");
    }


}
else{
    echo("<br>To potentially view this page, please <a href='login/login_user.php'>log in</a> first.");
}

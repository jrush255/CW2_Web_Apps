<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Home Page</title>
</head>

<body>


</body>
</html>


<?php

//Start of Nav stuff
echo ('
    <ul>
    <li><a class="active" href="index.php">Home</a></li>
    
    <li style="float:right"><a href="login/login_user.php">Log in</a></li>
    ');

session_start();

if(isset($_SESSION["level"])) {
    echo('
            <li><a href="tasks/view_task.php">View Tasks</a></li>
         ');
    if ($_SESSION["level"] != "Guest") {
        echo('
                <li><a href="tasks/create_task.php">Create Tasks</a></li>
                <li><a href="tasks/edit_task.php">Edit Tasks</a></li>
                <li style="float:right"><a href="logout/logout_user.php">Log Out</a></li>
            ');
    }
    if ($_SESSION["level"] == "Admin") {
        echo ('
                <li><a href="tasks/view_all_tasks.php">View All Tasks</a></li>
                <li style="float:right"><a href="registration/created_user.php">Register</a></li>
                <li style="float:right"><a href="remove/user_visibility.php">Manage User Visibility</a></li>
              ');

    }
    echo ('</ul>');
}
else{
    echo ('</ul>');
    echo ('<br><h2><a href="login/login_user.php">Log in</a> to view this page.</h2>');
}
//End Nav Stuff



//Restrict options based on user level (eg more options for admin, less for guest)


if(isset($_SESSION["code"])){
    echo ("Welcome " . $_SESSION["user"]);

    echo("<br><br> What would you like to do today " . $_SESSION["user"] . "? <br>


");



}

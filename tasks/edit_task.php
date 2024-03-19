<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <title>Edit Tasks</title>
</head>

<body>

</body>
</html>

<?php
//Start of Nav stuff
echo ('
    <ul>
    <li><a href="../index.php">Home</a></li>
    
    <li style="float:right"><a href="../login/login_user.php">Log in</a></li>
    ');

session_start();


if(isset($_SESSION["level"])) {
    echo('
            <li><a href="view_task.php">View Tasks</a></li>
         ');
    if ($_SESSION["level"] == "User" or $_SESSION["level"] == "Guest") {
        echo('
                <li><a href="create_task.php">Create Tasks</a></li>
                <li><a class="active" href="edit_task.php">Edit Tasks</a></li>
                <li style="float:right"><a href="../logout/logout_user.php">Log Out</a></li>
            ');
        //End User/Guest Nav

        //Drop-down list to select a task
        //Then outputs selected task information on webpage



//Need an "isset Post, Submit task selection" here

    }
    if ($_SESSION["level"] == "Admin") {
        echo ('
                
                <li style="float:right"><a href="../registration/created_user.php">Register</a></li>
                <li style="float:right"><a href="../remove/user_visibility.php">Manage User Visibility</a></li>
              ');

    }
    echo ('</ul>');
}
else{
    echo ('</ul>');
    echo ('<br><h2><a href="../login/login_user.php">Log in</a> to view this page.</h2>');
}






//Form appears with fields for each section that allows user to input stuff and overwrite task entry on database
// // Handle form submission for adding items
//SQL Code to output task title to drop down box
//DD Box to select task, click button to submit
//Outputs task info (username (admin option), title, contents, progress, priority, hidden (admin option), modified date)

//Boxes to edit task then appear
//button to submit edits
//SQL code for updating that row
//Output (error/successful)




//Use this code for outputting the current info for tasks
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {}


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

//Start of Nav
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
    if ($_SESSION["level"] == "Guest"){
        echo ('</ul>');
        echo ('<br><h2>You do not have permission to access this page.</h2>');
    }
    if ($_SESSION["level"] == "User" or $_SESSION["level"] == "Admin") {
        echo('
                <li><a href="create_task.php">Create Tasks</a></li>
                <li><a class="active" href="edit_task.php">Edit Tasks</a></li>
                <li style="float:right"><a href="../logout/logout_user.php">Log Out</a></li>
            ');
    }
    if ($_SESSION["level"] == "Admin") {
        echo ('
                <li><a href="view_all_tasks.php">View All Tasks</a></li>
                <li style="float:right"><a href="../registration/created_user.php">Register</a></li>
                <li style="float:right"><a href="../remove/user_visibility.php">Manage User Visibility</a></li>
                <li style="float:right"><a href="../registration/user_access.php">Manage User Access</a></li>
              ');

    }
    echo ('</ul>');
}
else{
    echo ('</ul>');
    echo ('<br><h2><a href="../login/login_user.php">Log in</a> to view this page.</h2>');
}
//End Nav

if(isset($_SESSION["level"]) and ($_SESSION["level"]) != "Guest") {

    $server_name = "localhost";
    $username = "WA_Update";
    $password = "da5uTkIz)0h8aJ[U";

    //Form for hiding (marking for deletion) users
    $conn = new mysqli($server_name, $username, $password);
    //Check connection
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    } else {
        echo("<br>");
    }

    if ($_SESSION["level"] == "User") {


    }

    if ($_SESSION["level"] == "Admin") {
        $sql = 'SELECT * FROM credentials.tasktable WHERE 1';
        $result = $conn->query($sql);

        echo("<h3>Select Task</h3>
                <form action='edit_task2.php' method='post'>
                Task Name: <select name='posted_task_title'>");

        //Outputs all usernames
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $data = $row['task_title'];
                echo '<option value="' . $data . '">'. $data . '</option>';
            }
        }

        echo('</select><br>Create: <input type="submit" id="submit" name="submit"><br><br>
            </form>');

    }
    else if($_SESSION["level"] == "User"){
        //Find tasks associated with user
        $user = $_SESSION["user"];

        echo("<h3>Select Task</h3>
                <form action='edit_task2.php' method='post'>
                Task Name: <select name='posted_task_title'>");

        $sql = 'SELECT * FROM credentials.tasktable WHERE username = ? AND task_hidden = "N"';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()){
                $data = $row['task_title'];
                echo '<option value="' . $data . '">'. $data . '</option>';
            }
        }
        echo('</select><br>Create: <input type="submit" id="submit" name="submit"><br><br>
            </form>');
    }
}


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <title>View Tasks</title>
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
            <li style="float:right"><a href="../logout/logout_user.php">Log Out</a></li>
         ');
    if ($_SESSION["level"] != "Guest") {
        echo('
                <li><a href="create_task.php">Create Tasks</a></li>
                <li><a href="edit_task.php">Edit Tasks</a></li>
            ');
    }
    if ($_SESSION["level"] == "Admin") {
        echo ('
                <li><a class="active" href="view_all_tasks.php">View All Tasks</a></li>
                <li style="float:right"><a href="../registration/created_user.php">Register</a></li>
                <li style="float:right"><a href="../remove/user_visibility.php">Manage User Visibility</a></li>
                <li style="float:right"><a href="../registration/user_access.php">Manage User Access</a></li>
              ');

    }
    echo ('</ul>');
}
else{
    echo ('</ul>');

}
//End Nav Stuff


//Server login
$server_name = "localhost";
$username = "WA_Select";
$password = "AZP3Y4F5Bdf1AYO.";

$conn = new mysqli($server_name, $username, $password);
//Check connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
} else {
    echo("<br>");
}

if (isset($_SESSION["level"]) and $_SESSION["level"] == "Admin") {

    $sql = 'SELECT * FROM credentials.tasktable WHERE 1';
    $result = $conn->query($sql);

    echo("
            <table>
            <tr>
                <th>Username</th>
                <th>Title</th>
                <th>Contents</th>
                <th>Progress</th>
                <th>Priority</th>
                <th>Completion Date</th>
                <th>Modified Date</th>
                <th>Task Hidden?</th>
            </tr>
            ");

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            $task_user = $row['username'];
            $title = $row['task_title'];
            $content = $row['task_content'];
            $progress = $row['task_progress'];
            $priority = $row['task_priority'];
            $completion = $row['task_completion_date'];
            $modified = $row['task_modified'];
            $hidden = $row['task_hidden'];
            echo("<tr>
                <td>$task_user</td>
                <td>$title</td>
                <td>$content</td>
                <td>$progress</td>
                <td>$priority</td>
                <td>$completion</td>
                <td>$modified</td>
                <td>$hidden</td>
             </tr>");
        }
    }

}
else if ($_SESSION["level"] == "User" or $_SESSION["level"] == "Guest") {
    echo("You do not have permission to manage users " . $_SESSION["user"] . ". You are a " . $_SESSION["level"]);
}
else{
    echo ('<br><h2><a href="../login/login_user.php">Log in</a> to view this page.</h2>');
}

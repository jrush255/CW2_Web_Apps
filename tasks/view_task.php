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
            <li><a class="active" href="view_task.php">View Tasks</a></li>
         ');
    if ($_SESSION["level"] != "Guest") {
        echo('
                <li><a href="create_task.php">Create Tasks</a></li>
                <li><a href="edit_task.php">Edit Tasks</a></li>
                <li style="float:right"><a href="../logout/logout_user.php">Log Out</a></li>
            ');
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
    echo("Connection Success <br>");
}
if(isset($_SESSION["level"])) {
    if ($_SESSION["level"] == "User" or $_SESSION["level"] == "Guest") {
        //Find tasks associated with user
        $user = $_SESSION["user"];

        $sql = 'SELECT * FROM credentials.tasktable WHERE username = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $result = $stmt->get_result();

        //Could add an option here to order tasks based on priority


        //Outputs all tasks in a table format
        echo("
        <table>
        <tr>
            <th>Title</th>
            <th>Contents</th>
            <th>Progress</th>
            <th>Priority</th>
            <th>Completion Date</th>
        </tr>
        ");

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $title = $row['task_title'];
                $content = $row['task_content'];
                $progress = $row['task_progress'];
                $priority = $row['task_priority'];
                $completion = $row['task_completion_date'];
                echo("<tr>
                <td>$title</td>
                <td>$content</td>
                <td>$progress</td>
                <td>$priority</td>
                <td>$completion</td>
             </tr>");
            }
        }


    }

    if ($_SESSION["level"] == "Admin") {

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
                echo("<tr>
                <td>$task_user</td>
                <td>$title</td>
                <td>$content</td>
                <td>$progress</td>
                <td>$priority</td>
                <td>$completion</td>
             </tr>");
            }
        }


    }

}


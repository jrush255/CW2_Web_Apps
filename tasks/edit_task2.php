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

    //Server log in and connection
    $server_name = "localhost";
    $username = "WA_Update";
    $password = "da5uTkIz)0h8aJ[U";
    $conn = new mysqli($server_name, $username, $password);
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    } else {
        echo("<br>");
    }

    //Get title from previous page
    $task_title_res = $_POST['posted_task_title'];

    //SQL
    $sql = 'SELECT * FROM credentials.tasktable WHERE task_title = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $task_title_res);
    $stmt->execute();
    $result = $stmt->get_result();

    //Output title info as table
    echo(" <h4>The current task contains this information</h4>
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

        echo ("</table>");
    }

    //Table for user to change task info
    echo('
    <h4>Edit Tasks</h4>
    <form action="edit_task3.php" method="post">
        <input type="hidden" id="oldTitle" name="oldTitle" value="' . $task_title_res . '"" required></input><br>
        Task Title: <input type="text" id="newTitle" name="newTitle" value="' . $title . '" required><br>
    Task Contents: <input type="text" id="newContent" name="newContent" value="' . $content . '"required><br>
    Task Progress:<select id="newProgress" name="newProgress" required>
        <option value="Not Started">Not Started</option>
        <option value="In Progress">In Progress</option>
        <option value="Paused">Paused</option>
        <option value="Completed">Completed</option>
    </select><br>
    Task Priority:<select id="newPriority" name="newPriority" required>
        <option value="High">High</option>
        <option value="Medium">Medium</option>
        <option value="Low">Low</option>
    </select><br>
    Completion Date: <input type="date" id="newCompletionDate" name="newCompletionDate" required><br>
    Hide Task?:<select id="hideTask" name="hideTask" required>
        <option value="N">No</option>
        <option value="Y">Yes</option>
        </select><br>
    ');

    if ($_SESSION["level"] == "Admin") {

        //Grabs usernames from database so admin can assign a task to a specific user
        $sql = 'SELECT * FROM credentials.methodone WHERE marked_for_deletion = "N"';
        echo("Select User: <select name='username'>");
        //Outputs all usernames
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $data = $row['username'];
                echo '<option value="' . $data . '">'. $data . '</option>';
            }

            //End of HTML form for admin
            echo('</select><br>Create: <input type="submit" id="submit" name="submit"><br><br>
            </form>');



        }

    }
    //End of HTML form for users
    else{
        echo('<br>Create: <input type="submit" id="submit" name="submit"><br><br>
            </form>');
    }
}


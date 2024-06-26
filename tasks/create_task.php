<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <title>Create Tasks</title>
</head>

<body>

</body>
</html>

<?php
function sort_input($data){
    $data = trim($data, " ");
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

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
                <li><a class="active" href="create_task.php">Create Tasks</a></li>
                <li><a href="edit_task.php">Edit Tasks</a></li>
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
}
//End Nav Stuff

if(isset($_SESSION["level"]) and $_SESSION["level"] != "Guest"){

    $server_name = "localhost";
    $username = "WA_Insert";
    $password = "Qx1XYuEStetL2@2z";

    //Establishing a new connection to the server
    $conn = new mysqli($server_name, $username, $password);

    if ($conn->connect_error)
    {
        die("Connection Failed: " . $conn->connect_error);
    }
    else{
        echo("<br>");
    }

    echo("<h1>Create a new task!</h1>");
    echo("
    <form action='create_task.php' method='post'>
        Task Title: <input type='text' id='title' name='title' required><br>
    Task Contents: <input type='text' id='content' name='content' required><br>
    Task Progress:<select id='progress' name='progress' required>
        <option value='Not Started'>Not Started</option>
        <option value='In Progress'>In Progress</option>
        <option value='Paused'>Paused</option>
        <option value='Completed'>Completed</option>
    </select><br>
    Task Priority:<select id='priority' name='priority' required>
        <option value='High'>High</option>
        <option value='Medium'>Medium</option>
        <option value='Low'>Low</option>
    </select><br>
    Completion Date: <input type='date' id='completionDate' name='completionDate' required><br>
    ");

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
                echo "<option value=$data>$data</option>";
            }

            //End of HTML form
            echo('</select><br>Create: <input type="submit" id="submit" name="submit"><br><br>
            </form>');

        }

        if(isset($_POST['submit'])){

            $TaskUsername = ($_POST['username']);

            //Grab the right user ID for the selected user from drop-down
            $sql = 'SELECT user_id FROM credentials.methodone WHERE username = ?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $TaskUsername);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $UserID = $row['user_id'];


            $TaskName = sort_input($_POST['title']);
            $TaskContent = sort_input($_POST['content']);
            $TaskProgress = $_POST['progress'];
            $TaskPriority = $_POST['priority'];
            $TaskCompletionDate = $_POST['completionDate'];

            try{
                $sql = "INSERT INTO credentials.tasktable (`user_id`, `username`, `task_title`, `task_content`, `task_progress`, `task_priority`, `task_completion_date`) VALUES (?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssssss', $UserID, $TaskUsername,$TaskName,$TaskContent,$TaskProgress,$TaskPriority,$TaskCompletionDate);
                if($stmt->execute()){
                    echo "Task added successfully!";
                }
            }
            catch(mysqli_sql_exception $error){
                $code = $error->getCode();
                if($code = 1062){
                    echo "Task with that title already exists";
                }
            }


        }
    }

    else if ($_SESSION["level"] == "User") {
        echo('Create: <input type="submit" id="submit" name="submit"><br><br></form>');

        if(isset($_POST['submit'])){
            $TaskName = sort_input($_POST['title']);
            $TaskContent = sort_input($_POST['content']);
            $TaskProgress = $_POST['progress'];
            $TaskPriority = $_POST['priority'];
            $TaskCompletionDate = $_POST['completionDate'];

            //Info obtained from session
            $UserID = $_SESSION["code"];
            $TaskUsername = $_SESSION["user"];

            try{
                $sql = "INSERT INTO credentials.tasktable (`user_id`, `username`, `task_title`, `task_content`, `task_progress`, `task_priority`, `task_completion_date`) VALUES (?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssssss', $UserID,$TaskUsername,$TaskName,$TaskContent,$TaskProgress,$TaskPriority,$TaskCompletionDate);
                if($stmt->execute()){
                    echo "Task added successfully!";
                }
            }
            catch(mysqli_sql_exception $error){
                $code = $error->getCode();
                if($code = 1062){
                    echo "Task with that title already exists";
                }
            }



        }

    }

}
else{
    echo ('<br><h2><a href="../login/login_user.php">Log in</a> to view this page.</h2>');
}

if ($_SESSION["level"] == "Guest"){
    echo ('<br><h2>You do not have permission to access this page.</h2>');
}


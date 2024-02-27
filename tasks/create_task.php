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

<h1>Create a new task!</h1>

<form action="create_task.php" method="get">
    Task Title: <input type="text" name="title" required><br>
    Task Contents: <input type="text" name="content" required><br>
    Completion Date: <input type="date" name="completionDate" required><br>
    Create: <input type="submit" name="submit"><br><br>
</form>

</body>
</html>

<?php

session_start();
//Remove session code later, needed now for testing
echo ("Hi " . $_SESSION["user"] . " " . $_SESSION["code"]);


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
    echo("Connection Success <br>");
}

//Checks for submission
if(isset($_GET['submit'])){
    $TaskName = $_GET['title'];
    $TaskContent = $_GET['content'];
    $TaskCompletionDate = $_GET['completionDate'];

    //TaskAssignee currently hardcoded - will be available from dropdown once implemented.
    //$TaskAssignee = $_SESSION["user"];

    //User_id currently hardcoded - will be available from SESSION once implemented.
    $user_id = $_SESSION["code"];

    $sql = "INSERT INTO credentials.tasktable (`user_id`, `task_title`, `task_content`, `task_completion_date`) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $user_id,$TaskName,$TaskContent,$TaskCompletionDate);
    if($stmt->execute()){
        echo "Task added successfully!";
    }
}

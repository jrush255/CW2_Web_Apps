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
function sort_input($data){
    $data = trim($data, " ");
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

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
    //Log into server
    $server_name = "localhost";
    $username = "WA_Update";
    $password = "da5uTkIz)0h8aJ[U";

    $conn = new mysqli($server_name, $username, $password);
    //Check connection
    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    } else {
        echo("<br>");
    }

    //Get entries from previous page
    $oldTitle = $_POST['oldTitle'];
    $newTitle = sort_input($_POST['newTitle']);
    $newContent = sort_input($_POST['newContent']);
    $newProgress = $_POST['newProgress'];
    $newPriority = $_POST['newPriority'];
    $newCompletionDate = $_POST['newCompletionDate'];
    $hideTask = $_POST['hideTask'];


    if ($_SESSION["level"] == "Admin") {
        $newUsername = $_POST['username'];

        //Grab the right user ID for the selected user from drop-down
        $sql = 'SELECT user_id FROM credentials.methodone WHERE username = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $newUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $newUserID = $row['user_id'];

        try{
            $sql = 'UPDATE credentials.tasktable SET user_id = ?, username = ?, task_title = ?, task_content = ?, task_progress = ?, task_priority = ?, task_completion_date = ?, task_hidden = ?, task_modified = now() WHERE task_title = ?;';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssssssss', $newUserID, $newUsername,$newTitle,$newContent,$newProgress,$newPriority,$newCompletionDate,$hideTask, $oldTitle);
            if($stmt->execute()){
                echo "Task updated successfully!";
            }
        }
        catch(mysqli_sql_exception $error){
            $code = $error->getCode();
            if($code = 1062){
                echo "Task with that title already exists";
            }
        }



    }
    else{

        try{
            $sql = 'UPDATE credentials.tasktable SET task_title = ?, task_content = ?, task_progress = ?, task_priority = ?, task_completion_date = ?, task_hidden = ?, task_modified = now() WHERE task_title = ?;';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssssss', $newTitle,$newContent,$newProgress,$newPriority,$newCompletionDate,$hideTask, $oldTitle);
            if($stmt->execute()){
                echo "Task updated successfully!";
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




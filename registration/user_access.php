<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <title>Modify Users</title>
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
            <li><a href="../tasks/view_task.php">View Tasks</a></li>
         ');
    if ($_SESSION["level"] != "Guest") {
        echo('
                <li><a href="../tasks/create_task.php">Create Tasks</a></li>
                <li><a href="../tasks/edit_task.php">Edit Tasks</a></li>
                <li style="float:right"><a href="../logout/logout_user.php">Log Out</a></li>
            ');
    }
    if ($_SESSION["level"] == "Admin") {
        echo ('
                <li><a href="../tasks/view_all_tasks.php">View All Tasks</a></li>
                <li style="float:right"><a href="created_user.php">Register</a></li>
                <li style="float:right"><a href="../remove/user_visibility.php">Manage User Visibility</a></li>
                <li style="float:right"><a class="active" href="../registration/user_access.php">Manage User Access</a></li>
                
              ');

    }
    echo ('</ul>');
}
else{
    echo ('</ul>');
    echo ('<br><h2><a href="../login/login_user.php">Log in</a> to view this page.</h2>');
}
//End Nav Stuff

if(isset($_SESSION["level"])){

    if($_SESSION["level"] == "Admin")
    {
        echo ("You are an Admin " . $_SESSION["user"] . ". Use this page to modify user access<br><br>");

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

        $sql = 'SELECT * FROM credentials.methodone WHERE marked_for_deletion = "N"';

        echo("<h3>Manage User Access</h3>
                <form action='user_access.php' method='post'>
                Select User: <select name='username'>");

        //Outputs all usernames
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $data = $row['username'];
                echo "<option value=$data>$data</option>";
            }
        }
        echo("</select><br>
            User Level: <select name='access_level'>
                <option value='Guest'>Guest</option>
                <option value='User'>User</option>
                <option value='Admin'>Admin</option>
                </select><br>
            Confirm: <input type='submit' name='submit'>
            </form>");

        if(isset($_POST['submit'])) {
            //Get response
            $user = $_POST['username'];
            $access_level = $_POST['access_level'];

            $sql = 'UPDATE credentials.methodone SET user_level = ?, last_updated = now() WHERE username = ?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $access_level, $user);
            $stmt->execute();

            echo("User " . $user . " has been updated to have " . $access_level . " permissions.");

            header('refresh: 2;');

        }


    }
    //Permission Denied:
    else if($_SESSION["level"] == "User" or $_SESSION["level"] == "Guest")
    {
        echo("You do not have permission to modify users " . $_SESSION["user"] . ". You are a " . $_SESSION["level"]);
    }
    else
    {
        echo("Error");
    }


}
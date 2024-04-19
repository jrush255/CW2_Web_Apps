<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <title>Create Users</title>
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
                <li style="float:right"><a class="active"  href="created_user.php">Register</a></li>
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

if(isset($_SESSION["level"])){

    if($_SESSION["level"] == "Admin")
    {
        echo ("You are an Admin " . $_SESSION["user"] . ". Use this page to create new users<br><br>");

        echo("<h1> Create new user account</h1>
            <form action='created_user.php' method='post'>
                Username: <input type='text' name='username' required><br>
                Forename: <input type='text' name='fname' required><br>
                Surname: <input type='text' name='sname' required><br>
                E-mail: <input type='email' name='email' required><br>
                Password: <input type='password' name='password' required><br>
                User Level: <select name='access_level'>
                <option value='Guest'>Guest</option>
                <option value='User'>User</option>
                <option value='Admin'>Admin</option>
                </select><br>
                Register: <input type='submit' name='submit'>
            </form>");

        //Login info
        $server_name = "localhost";
        $username = "WA_Insert";
        $password = "Qx1XYuEStetL2@2z";

        //Create connection
        $conn = new mysqli($server_name, $username, $password);
        //Check connection
        if ($conn->connect_error)
        {
            die("Connection Failed: " . $conn->connect_error);
        }
        else{
            echo("Connection Success <br>");
        }

        //Runs after input information has been submitted
        if(isset($_POST['submit'])){

            $user = sort_input($_POST['username']);
            $fname = sort_input($_POST['fname']);
            $sname = sort_input($_POST['sname']);
            $email = sort_input($_POST['email']);
            $user_password = sort_input($_POST['password']);
            $access_level = $_POST['access_level'];

            try {
                $sql = "INSERT INTO credentials.methodone (`username`, `forename`, `surname`, `email`, `password`, `user_level`) VALUES (?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
                $stmt->bind_param('ssssss', $user, $fname, $sname, $email, $hashed_password, $access_level);
                if ($stmt->execute()) {
                    echo "The " . $access_level . " " . $user . " has been created!";
                }
            }
            catch(mysqli_sql_exception $error){
                $code = $error->getCode();
                if($code = 1062){
                    echo "User already exists";
                }
            }
        }



    }
    //Users and Guests cannot create users
    else if($_SESSION["level"] == "User" or $_SESSION["level"] == "Guest")
    {
        echo("You do not have permission to register users " . $_SESSION["user"] . ". You are a " . $_SESSION["level"]);
    }
    //Hopefully shouldn't see this message
    else
    {
        echo("PLZ FIX");
    }


}
else{
    echo("<br>To potentially view this page, please <a href='../login/login_user.php'>log in</a> first.");
}





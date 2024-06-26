<html>
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <title>Log in</title>
</head>
<body>
<ul>
    <li><a href="../index.php">Home</a></li>
    <li style="float:right"><a class="active" href="login_user.php">Log in</a></li>
</ul>


<h1> Log in with an existing account</h1>

<form action="login_user.php" method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" name="submit">
</form>

</body>
</html>

<?php
function sort_input($data){
    $data = trim($data, " ");
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

session_start();

//Login info
$server_name = "localhost";
$username = "WA_Select";
$password = "AZP3Y4F5Bdf1AYO.";

//Create connection
$conn = new mysqli($server_name, $username, $password);
//Check connection
if ($conn->connect_error)
{
    die("Connection Failed: " . $conn->connect_error);
}
else{
    echo("<br>");
}

//Checks there is an input before searching database
if(isset($_POST['submit'])){
    $user = sort_input($_POST['username']);
    $user_password = sort_input($_POST['password']);

    //Check username
    $sql = 'SELECT * FROM credentials.methodone WHERE username = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $user);


    if ($stmt->execute()) {
        //Getting Result
        $res = $stmt->get_result();
        if($res->num_rows > 0){
            //Grab information from row
            $row = $res->fetch_assoc();
            //Selects specific column
            $hash = $row['password'];

            //Used later for setting session
            $userID = $row['user_id'];
            $permissions = $row['user_level'];


            $options = array('cost => 11');

            //Verifying User Password
            // Verify stored hash against plain-text password
            if (password_verify($user_password, $hash)) {
                // Check if a newer hashing algorithm or change in cost
                if (password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {
                    // If yes, a new hash is created to replace the previous one
                    $newHash = password_hash($password, PASSWORD_DEFAULT, $options);
                }


                $_SESSION ["code"] = $userID;
                $_SESSION["user"] = $user;
                $_SESSION["level"] = $permissions;

                echo ("Welcome " . $user . "You have " . $_SESSION["level"] . " permissions. ");

                echo("<br><br><a href=../index.php>Go to user homepage</a>");

                $sql = 'SELECT require_pass_reset FROM credentials.methodone WHERE username = ?';
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $_SESSION["user"]);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $passReset = $row['require_pass_reset'];

                if($passReset == "Y"){
                    header('Location: /Assignment%20Website/login/change_password.php');
                }




            }
            else{
                echo ("Incorrect Username or Password!");
            }

        }
        else {
            echo ("Error - User does not exist!");

        }
    }
}




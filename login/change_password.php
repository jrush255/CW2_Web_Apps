<html>
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <title>Change Password</title>
</head>
<body>
<ul>
    <li><a href="../index.php">Home</a></li>
    <li style="float:right"><a class="active" href="login_user.php">Log in</a></li>
</ul>

<h1>Please change your password:</h1>

<form action="change_password.php" method="post">
    Password: <input type="password" name="password1" required><br>
    Repeat Password: <input type="password" name="password2" required><br>
    <input type="submit" name="submit">
</form>

</body>
</html>

<?php
//Function to sanitise user input
function sort_input($data){
    $data = trim($data, " ");
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

session_start();

if(isset($_POST['submit'])){
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


    $UserID = $_SESSION["code"];
    $password1 = sort_input($_POST['password1']);
    $password2 = sort_input($_POST['password2']);

     if($password1 != $password2){
         echo("Passwords do not match");
     }

    $hashed_new_password = password_hash($password2, PASSWORD_DEFAULT);

    $sql = "UPDATE credentials.methodone SET password = ? WHERE user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $hashed_new_password, $UserID);
    if ($stmt->execute()) {
        echo ("Password Changed Successfully!");
    }
    else{
        echo("Oops, it broke");
    }

}
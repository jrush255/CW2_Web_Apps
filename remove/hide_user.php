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
</body>
</html>

<?php
session_start();

if(isset($_SESSION["level"])) {

    if ($_SESSION["level"] == "Admin") {
        echo("You are an Admin " . $_SESSION["user"] . ". Use this page to hide or remove users<br><br>");

        //Used for outputting users to a drop-down list
        $server_name = "localhost";
        $username = "WA_Select";
        $password = "AZP3Y4F5Bdf1AYO.";


        //Form for hiding (marking for deletion) users
        $conn = new mysqli($server_name, $username, $password);
        //Check connection
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        } else {
            echo("Connection Success <br>");
        }
        //Get list of usernames
        //Output to drop-down in form

        //Find user
        $sql = 'SELECT * FROM credentials.methodone WHERE marked_for_deletion = "N"';

        echo("<h3> Hide User</h3>
                <form action='hide_user.php' method='post'>
                Select User: <select name='username'>");

        //Outputs all usernames - now to put it in a form
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $data = $row['username'];
                echo "<option value=$data>$data</option>";
            }
        }

        echo ("</select><br>
            Confirm: <input type='submit' name='submit_temp_del'>
            </form>");

        //Form for reinstating users
        $sql = 'SELECT * FROM credentials.methodone WHERE marked_for_deletion = "Y"';

        echo("<h3>Reinstate User</h3>
                <form action='hide_user.php' method='post'>
                Select User: <select name='username'>");
        //Get and output usernames
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data = $row['username'];
                echo "<option value=$data>$data</option>";
            }
        }

        echo ("</select><br>
            Confirm: <input type='submit' name='submit_reinstate'>
            </form>");


        //Code for permanently deleting users
        $sql = 'SELECT * FROM credentials.methodone WHERE marked_for_deletion = "Y"';

        echo("<h3>Delete User</h3>
                <form action='hide_user.php' method='post'>
                Select User: <select name='username'>");
        //Get and output usernames
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data = $row['username'];
                echo "<option value=$data>$data</option>";
            }
        }
        echo ("</select><br>
            Confirm: <input type='submit' name='submit_perm_del'>
            </form>");

        //close connection to free up later
        $conn->close();
        // Do need this as the connection will need to re-open as either delete or update
        //change



        //Code for hiding users
        if (isset($_POST['submit_temp_del'])) {

            $server_name = "localhost";
            $username = "WA_Update";
            $password = "da5uTkIz)0h8aJ[U";

            $conn = new mysqli($server_name, $username, $password);
            //Check connection
            if ($conn->connect_error) {
                die("Connection Failed: " . $conn->connect_error);
            } else {
                echo("Connection Success <br>");
            }

            //Set the drop-down selection as variable
            $hide_user = $_POST['username'];

            //Statement
            $sql = 'UPDATE credentials.methodone SET marked_for_deletion = "Y" WHERE username = ?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $hide_user);

            try {

                $stmt->execute();

                //for testing
                echo($hide_user);


            } //Error handling for missing record
            catch (mysqli_sql_exception $error) {
                $code = $error->getCode();
                if ($code = 1032) {
                    echo "Can't find specified user.";
                }
            }
        }

        /*

        //Code for deleting users
        if (isset($_POST['submit_perm_del'])) {

            $server_name = "localhost";
            $username = "WA_Delete";
            $password = "RF]-fobOPlvp4mQB";

            //Create connection
            $conn = new mysqli($server_name, $username, $password);
            //Check connection
            if ($conn->connect_error) {
                die("Connection Failed: " . $conn->connect_error);
            } else {
                echo("Connection Success <br>");
            }


            //submit for a selected user in a drop-down box

            try {
                //SQL statement to find a user with marked_for_deletion set to 1

                //Another SQL statement to remove record from database


            } //Error handling for missing record
            catch (mysqli_sql_exception $error) {
                $code = $error->getCode();
                if ($code = 1032) {
                    echo "Can't find specified user.";
                }
            }
        }
*/

    }



    //Users and Guests cannot use this page
    else if ($_SESSION["level"] == "User" or $_SESSION["level"] == "Guest") {
        echo("You do not have permission to remove users " . $_SESSION["user"] . ". You are a " . $_SESSION["level"]);
    }
    //Hopefully shouldn't see this message
    else {
        echo("PLZ FIX");
    }
}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XYZ Company</title>
    <link rel ="stylesheet" type="text/css" href ="css/style.css">
</head>
<body>
<header>For Event <br><br>User Login</header>
<form method ="post" >
    Email:<input type ='text' name="searchFor" > <br><br>
    Password/RegistrationId:<input type ='password' name="search"><br><br>
    <input type ="submit" value="Login" name="submit"/>
    <input type ="submit" value="Log Out" name="back"/>
</form>

<?php
/**
 * Created by PhpStorm.
 * User: shi
 * Date: 18-10-2018
 * Time: 12:23
 */

include("Include_DB_Connect.php");
session_start();

//Read form data
//if we use name="submit " its easy for all form fields
if(isset($_POST["submit"])) {


    if (isset($_POST["searchFor"])) {

        if (empty($_POST["searchFor"]) || empty($_POST["search"]))
            echo "Please fill all the fields..";
        else {

            $con = db_connect();

            $keyword = mysqli_real_escape_string($con, $_POST['search']);
            $email = mysqli_real_escape_string($con, $_POST['searchFor']);

            $query = "SELECT * FROM Employee where Email='" . $email . "' and userId='" . $keyword . "'";
            $result = mysqli_query($con, $query);
            $row = mysqli_num_rows($result);

            if ($row != 0) {
                echo "User successfully logged in.";

                $_SESSION['username'] = $email;

                if ($email == 'admin@gmail.com')
                    header('location:Admin_page.php');

                else
                {

                    $query = "SELECT * from employee where groupId in (select groupId from employee where userID=$keyword )
                       and  userId <> '$keyword'";

                    $result = mysqli_query($con,  $query ) or die ("query is failed" . mysqli_error($con));
                    echo "<br>";
                    echo "Your group members are: <br>";
                    echo "<table align='center' border='1'><th>Group member Name</th><th>Cell Number</th>";

                    while (($row = mysqli_fetch_row($result)) == true) {
                        if($row[2]!=='admin') {
                        echo"<tr><td>".$row[2]."</td><td>".$row[0]."</td></tr>";
                    }}
                    echo "<table/>";
                    }
            } else
                echo "Login failed...";
            mysqli_close($con);
        }
    }

}
if (isset($_POST["back"])) {
    session_destroy();

    header("location:Login.php");

    exit();


}
?>

</body>
</html>
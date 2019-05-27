<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XYZ Company</title>
    <link rel ="stylesheet" type="text/css" href ="css/style.css">
</head>
<body>
<header>Please Register For Event <br><br> Create User</header>
<form method="post" >
    <table align="center">
        <tr><td>Name :</td> <td><input type="text" name="pname"/></td></tr>
        <tr><td>Cell phone : </td> <td><input type="text" name="pcell"/></td></tr>
        <tr><td>Email :</td> <td><input type="text" name="pmail"/></td></tr>
        <tr><td>Address :</td> <td><input type="text" name="paddr"/></td></tr>
        <tr><td>Event :</td> <td><select type="text" id="search1" name="search1">
    <?php

    // declaring some variables
    $host = "localhost";
    $user = "root";
    $password = "";
    $dbName = "XYZ";
    $Name='';
    $Serial='';
    $PurchaseDate='';
    $colour='';
    //$Search='Bottle';]
    $Selected=true;

    //Connect to the Server+Select DB
    $con = mysqli_connect($host, $user, $password, $dbName)
    or die("Connection is failed");

    //$Name = $_POST['name'];
    $query = "Select * from Events";
    $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
    while (($row = mysqli_fetch_row($result)) == true) {
        $EventId = $row[0];
        $EventName = $row[1];
        if($Selected==true) {
            echo '<option selected value="' . $EventId . '">' . $EventName . '</option>"';
            $Selected = false;
        } else echo '<option value="' . $EventId . '">' . $EventName . '</option>"';

    }
    mysqli_close($con);
    ?></select></td></tr>
    </table>
    <input type="submit" value="Create" name="register"/>
    <input type ="submit" value="Back" name="back"/>

</form>

<?php
include('Include_DB_Connect.php');
include('Include_find_user.php');
session_start();
if (isset($_POST["register"])) {

    if ( empty($_POST["pcell"]) ||empty($_POST["search1"]) ||
        empty($_POST["pmail"]) || empty($_POST["pname"]) || empty($_POST["paddr"]))
        echo "Please fill all the fields..";
    else {
        $con = db_connect();

        $pcell = mysqli_real_escape_string($con, $_POST["pcell"]);
        $pmail = mysqli_real_escape_string($con, $_POST["pmail"]);
        $pname = mysqli_real_escape_string($con, $_POST["pname"]);
        $paddr = mysqli_real_escape_string($con, $_POST["paddr"]);
        $pevent = mysqli_real_escape_string($con, $_POST["search1"]);
        $userId=rand(1,1000000);


        $result = find_user($con,$pevent);

        if (mysqli_num_rows($result) != 0)
            echo "User is already created...";
        else {


            $query = "insert into Employee values ('$pcell','$pmail','$pname','$paddr',' $userId','$pevent',0)";
            $result1 = mysqli_query($con, $query);
            if ($result1){
                echo "User created succesfully...<br>";
           $query = "Select * from Employee WHERE userId = $userId";
           $result1 = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));

            while (($row = mysqli_fetch_row($result1)) == true) {

                echo "Your userId is :  ".$row[4];
            }

            }
            else
                echo "Insert is failed : " . mysqli_error($con);
        }
//close connection
        mysqli_close($con);
    }

}
if (isset($_POST["back"])) {
    header('location:Main_page.html');
}
?>

</body>
</html>
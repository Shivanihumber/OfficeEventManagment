<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>XYZ Company</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>


<?php

// declaring some variables
$host = "localhost";
$user = "root";
$password = "";
$dbName = "XYZ";
$Name = '';
$Serial = '';
$PurchaseDate = '';
$colour = '';

$Selected = true;

//Connect to the Server+Select DB
$con = mysqli_connect($host, $user, $password, $dbName)
or die("Connection is failed");
if (isset($_POST['UPDATE'])) {
    $userId = $_POST['userId'];
    $query = "Select * from Employee where  userId=$userId";
    $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));

    if (($row = mysqli_fetch_row($result)) == true) {
        $cellphone = $row[0];
        $Email = $row[1];
        $Name = $row[2];
        $Address = $row[3];

        echo '<form method="post" >
                                  <table align="center">
                                    <tr><td>Name :</td> <td><input type="text" name="pname" value="' . $Name . '"/></td></tr>
                                    <tr><td>Cell phone : </td> <td><input type="text" name="pcell" value="' . $cellphone . '"/></td></tr>
                                    <tr><td>Email :</td> <td><input type="text" name="pmail" value="' . $Email . '"/></td></tr>
                                    <tr><td>Address :</td> <td><input type="text" name="paddr" value="' . $Address . '"/></td></tr></table>
                                       <input type="submit" value="Update" name="save"/>
                                       <input type="submit" value="Cancel" name="cancelSave"/>
                                       <input type="hidden" value="' . $userId . '" name="userId"/>
                                  </form>';

    }
} else {
    echo '<form method="post">
    <table align="center">
        <tr>
            <td>
                Group By :<input type="text" placeholder="Number" name="number" />
            </td>
            <td>
                <table align="center">
                    <tr><td><input type="submit" value="Total Num Of Groups" name="groupByButton" /></td></tr>
                    <tr><td><input type="submit" value="Users In Each Group" name="groupByButton" /></td></tr>
                </table> <td>For Event :</td> <td><select type="text" id="search1" name="search1">';


    //$Name = $_POST['name'];
    $query = "Select * from Events";
    $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
    while (($row = mysqli_fetch_row($result)) == true) {
        $EventId = $row[0];
        $EventName = $row[1];
        if ($Selected == true) {
            echo '<option selected value="' . $EventId . '">' . $EventName . '</option>"';
            $Selected = false;
        } else echo '<option value="' . $EventId . '">' . $EventName . '</option>"';

    }
    mysqli_close($con);
    echo '</select></td>
            </td>
        </tr>
    </table>


</form>';


// declaring some variables
    $host = "localhost";
    $user = "root";
    $password = "";
    $dbName = "XYZ";
    $Name = '';
    $City = '';

//Connect to the Server+Select DB
    $con = mysqli_connect($host, $user, $password, $dbName)
    or die("Connection is failed");

    //log out
    if (isset($_POST["back"])) {
        session_unset();
        session_destroy();
        header("Location: Login.php");

        exit();

    }

    //Save from update page
    if (isset($_POST["save"])) {
        $userId = $_POST['userId'];
        $pcell = mysqli_real_escape_string($con, $_POST["pcell"]);
        $pmail = mysqli_real_escape_string($con, $_POST["pmail"]);
        $pname = mysqli_real_escape_string($con, $_POST["pname"]);
        $paddr = mysqli_real_escape_string($con, $_POST["paddr"]);


        $query1 = "update  Employee set  cellphone='$pcell',Email='$pmail',employee.name='$pname',Address='$paddr' where userid=$userId";
        $result1 = mysqli_query($con, $query1) or die ("query is failed" . mysqli_error($con));
        if ($result1) {
            echo "User updated successfully...<br>";

            $result1 = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));


        }
    }

//Delete
    if (isset($_POST['DELETE'])) {
        $userId = $_POST['userId'];

        $query = "Delete from Employee where userId = '$userId'";
        $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
    }


//group by total number of groups
    if (isset($_POST['groupByButton'])) {
        //this is the value of the submit button clicked
        $action = $_POST['groupByButton'];
        $number = $_POST['number'];
        $eventId = $_POST['search1'];

        if (empty($_POST["number"]))
            echo "Please fill all the fields..";
        else {

            $query = "Delete from groups where groups.eventid = '$eventId'";
            $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));

            $query = "update Employee set groupid = 0 where eventId = '$eventId'";
            $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));

            $query = "Select * from employee where eventId = '$eventId'";
            $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
            $empRowCount = mysqli_num_rows($result) or die ("query is failed" . mysqli_error($con));

            if ($action == "Users In Each Group") {

                if ($empRowCount == 0) {
                    $number = 0;
                } else {
                    $numberOfGroups = round($empRowCount / $number);
                    if ($empRowCount % $number > 0) {
                        $numberOfGroups++;
                    }
                }
                $numberInEachGroup = $number;
            }
            // action = "Total Num Of Groups"
            else {
                $numberOfGroups = $number;
                $numberInEachGroup = round($empRowCount / $number);
                if ($empRowCount % $number > 0) {
                    $numberInEachGroup++;
                }
            }

            for ($i = 1; $i <= $numberOfGroups; $i++) {
                $query = "insert into groups (Status,GroupNumber,eventId)value('Full',$i,$eventId)";
                $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
            }

            $query = "Select groupId from groups where eventId = '$eventId'";
            $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));

            $groupIdArr = Array();
            $count = 0;
            //all newly generated group ids are fetched and put into an array
            while (($row = mysqli_fetch_row($result)) == true) {
                $groupIdArr[$count++] = $row[0];
            }

            $query = "Select userId from employee where eventId = '$eventId'";
            $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
            $userIdArr = Array();
            $count = 0;
            //fetch all users for the event id and put them in an array
            while (($row = mysqli_fetch_row($result)) == true) {
                $userIdArr[$count++] = $row[0];
            }

            // we now assign each user a group by updating the user table
            $groupCounter = 0;
            for ($i = 0; $i < sizeof($userIdArr); $i++) {
                if ($groupCounter == sizeof($groupIdArr)) {
                    $groupCounter = 0;
                }
                $groupId = $groupIdArr[$groupCounter++];
                $query = "update Employee set groupId = $groupId where userId = $userIdArr[$i]";
                $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
            }


            //update the status for groups having less than expected
            $query = "Select groupId, count(1) from employee where eventId = '$eventId' group by groupId";
            $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
            while (($row = mysqli_fetch_row($result)) == true) {
                if ($row[1] < $numberInEachGroup) {
                    $query = "update groups set status='waiting for more people' where groupId = '$row[0]'";
                    $resultTemp = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
                }
            }
        }
    }
    $query = "SELECT
    cellphone,
    Email,
    employee.name,
    address,
    groups.groupNumber,
    eventname,
    userId
FROM
    Employee LEFT JOIN events ON employee.eventId = EVENTS.eventId
    LEFT JOIN groups on employee.groupid = groups.groupid";
    $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' align='center'>";
        echo "<tr><th>CellPhone</th><th>Email</th><th>Name</th><th>Address</th><th>Group</th><th>Event</th><th>Action</th></tr>";
        while (($row = mysqli_fetch_row($result)) == true) {
            if($row[2]!=='admin') {
                echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td>
    <td><form method=\"post\"><input type=\"submit\" value=\"Update\" name=\"UPDATE\"/>
    <input type=\"submit\" value=\"Delete\" name=\"DELETE\"/><input type='hidden' value='$row[6]' name='userId'/></form></td>
    </tr>";
            }
        }
        echo "</table>";

    }
    $query = "select groupnumber,
                    status,
                    eventname
              from events,groups
              where events.eventId=groups.eventId";
    $result = mysqli_query($con, $query) or die ("query is failed" . mysqli_error($con));
    if (mysqli_num_rows($result) > 0) {
        echo "<br/><table border='1' align='center'>";
        echo "<tr><th>GroupNumber</th><th>Status</th><th>EventName</th></tr>";
        while (($row = mysqli_fetch_row($result)) == true) {
            echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>";
        }
        echo "</table>";
    }

    echo '<form method="post">
    <p>

        <input type ="submit" value="Logout" name="back"/>
    </p>
</form>';
} ?>
</body>
</html>



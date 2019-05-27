<?php

function find_user($con,$email){
    if (empty($email))
        $query = "select * from Employee";
    else
        $query = "select * from Employee where email = '$email'";

    $result = mysqli_query($con, $query)
    or die ("Query is failed : " . mysqli_error($con));
    return $result;
}

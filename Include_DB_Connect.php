<?php

function db_connect(){
    $host = "localhost";
    $user = "root";
    $pw = "";
    $dbName = "XYZ";
    $con = mysqli_connect($host, $user, $pw, $dbName)
    or die ("Connection is failed : " . mysqli_connect_error());
    return $con;
}

<?php
$db_server="localhost";
$db_user="root";
$db_password="";
$db_name="mandal_maven";
try{
    $conn = mysqli_connect($db_server,$db_user,$db_password,$db_name);
    // echo"Connection Successful";
}
catch(mysqli_sql_exception){
    echo "Connection Failed! Please try again";
}
?>
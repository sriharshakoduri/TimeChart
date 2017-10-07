<?php

/* Attempt MySQL server connection. Assuming you are running MySQL

server with default setting (user 'root' with no password) */

$link = mysqli_connect("localhost", "root", "", "mydb");



// Check connection

if($link === false){

    die("ERROR: Could not connect. " . mysqli_connect_error());

}



// Escape user inputs for security
$date = mysqli_real_escape_string($link, $_REQUEST['datepicker']);

$Act_name = mysqli_real_escape_string($link, $_REQUEST['ActName']);

$ActTime = mysqli_real_escape_string($link, $_REQUEST['ActTime']);





// attempt insert query execution

$sql = "INSERT INTO addactivity (date, name, time) VALUES ('$date','$Act_name', '$ActTime')";

if(mysqli_query($link, $sql)){

    echo "Records added successfully.";

} else{

   echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);

}



// close connection

mysqli_close($link);
?>


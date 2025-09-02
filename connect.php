<?php
$servername = "localhost";
$user = "root";
$pass = "";
$database = "store";

$con = new mysqli($servername, $user, $pass);

if ($con -> connect_error)
{
    die ("Connection failed: ". $con -> connect_error);
}
else
{
    echo "Connection successfull";
}

?>
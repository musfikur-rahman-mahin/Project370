<?php
session_start();
require '../connect.php';
// Check if user is logged in
if (!isset($_SESSION['admin_id'])|| $_SESSION['type'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

$u_id = $_POST['u_id'];
$action = $_POST['action'];

if ($action == 'Ban') {
    $sql_query = "UPDATE user SET flag=TRUE WHERE u_id=$u_id";
} else { 
    $sql_query = "UPDATE user SET flag=FALSE WHERE u_id=$u_id";
}

if ($conn->query($sql_query) == TRUE) {
    header("Location: manage_user.php");
} else {
    echo "<p class='error'>Error: " . $conn->error . "</p>";
}
?>

<?php
session_start();
require '../connect.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../frontend/index.html");
    exit();
}

?>


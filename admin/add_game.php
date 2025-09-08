<?php
session_start();
require '../connect.php';
// Check if user is logged in
if (!isset($_SESSION['admin_id'])|| $_SESSION['type'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$space = $_POST['space'];

// add new game
$sql_query = "INSERT INTO Game (name, description, price, space) VALUES ('$name', '$description', $price, $space)";

if ($conn->query($sql_query) == TRUE) {
    header("Location: manage_game.php");
} else {
    echo "<p class='error'>Error: " . $conn->error . "</p>";
}

?>
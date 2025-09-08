<?php
session_start();
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'user') {
    header("Location: index.php");
}
include '../connect.php';

$u_id = $_SESSION['u_id'];
$game_id = $_POST['game_id'];

// Get game price and user coins
$sql_query = "SELECT price FROM Game WHERE game_id=$game_id";
// sql query
$result = $conn->query($sql_query);
$row = $result->fetch_assoc();
$price = $row['price'];

$sql_query = "SELECT coin FROM user WHERE u_id=$u_id";
// sql query
$result = $conn->query($sql_query);
$row = $result->fetch_assoc();
$coin = $row['coin'];

if ($coin >= $price) {
    // Deduct coins
    $new_coin = $coin - $price;
    $sql_query = "UPDATE user SET coin=$new_coin WHERE u_id=$u_id";
    // sql query
    $conn->query($sql_query);
    
    // Add to Buy
    $sql_query = "INSERT INTO buy (u_id, game_id) VALUES ($u_id, $game_id)";
    // sql query
    $conn->query($sql_query);
    
    // Add to user_Game_List
    $sql_query = "INSERT INTO user_Game_List (u_id, game_id) VALUES ($u_id, $game_id)";
    // sql query
    $conn->query($sql_query);
    
    echo "<p class='success'>Game bought! <a href='gallery.php'>Back</a></p>";
} else {
    echo "<p class='error'>Not enough coins. <a href='gallery.php'>Back</a></p>";
}

$conn->close();
?>
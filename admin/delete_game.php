<?php
session_start();
require '../connect.php';
// Check if user is logged in
if (!isset($_SESSION['admin_id'])|| $_SESSION['type'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}


$game_id = $_POST['game_id'];

// delete from buy
$sql_query = "DELETE FROM buy WHERE game_id = $game_id";
$conn->query($sql_query);

// delete from user_game_list
$sql_query = "DELETE FROM user_game_list WHERE game_id = $game_id";
$conn->query($sql_query);

// delete from Game
$sql_query = "DELETE FROM Game WHERE game_id = $game_id";
if ($conn->query($sql_query) == TRUE) {
    header("Location: manage_game.php");
} else {
    echo "<p class='error'>Error deleting game: " . $conn->error . "</p>";
}

?>
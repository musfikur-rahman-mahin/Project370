<?php
session_start();
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'user') {
    header("Location: index.php");
}
include '../connect.php';

$u_id = $_SESSION['u_id'];
$game_id = $_POST['game_id'];

// delete from user_game_list
$sql_query = "DELETE FROM user_game_list WHERE u_id=$u_id and game_id=$game_id";
$conn->query($sql_query);

if ($conn->query($sql_query) == TRUE) {
    header("Location: user_game_list.php");
} else {
    echo "<p class='error'>Error deleting game: " . $conn->error . "</p>";
}

?>
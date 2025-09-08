<?php
session_start();
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'user') {
    header("Location: index.php");
}
include '../connect.php';

$u_id = $_SESSION['u_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $game_id = $_POST['game_id'];
    $star = $_POST['value'];
}

$sql_query = "UPDATE user_game_list SET user_rating=$star where game_id=$game_id AND u_id=$u_id";
$conn->query($sql_query);

$sql_query = "UPDATE game SET rating = 
                            (SELECT AVG(user_rating) 
                            FROM user_game_list 
                            WHERE game_id=$game_id AND user_rating > 0) 
              WHERE game_id=$game_id";
$conn->query($sql_query);


header("Location: user_game_list.php");
exit();
?>
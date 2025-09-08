<?php
session_start();
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'user') {
    header("Location: index.php");
}
include '../connect.php';

$u_id = $_SESSION['u_id'];

// List all games 
$sql = "SELECT * FROM Game";
// sql query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Games</title>
    <link rel="stylesheet" href="style.css">
    <style>
    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: center;
    }
    th {
        background-color: #f4f4f4;
    }
    tr:nth-child(even) {
        background-color: #fafafa;
    }
</style>

</head>
<body>
    <h1>Available Games</h1>
    <table>
        <tr><th>Name</th><th>Description</th><th>Rating</th><th>Price</th><th>Action</th></tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['rating'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                // Check if already owned (Read)
                $game_id = $row['game_id']; 
                $buy_sql = "SELECT * FROM user_game_list WHERE u_id=$u_id AND game_id=$game_id";
                // sql query
                $buy_result = $conn->query($buy_sql);
                // if user has that game the game won't have buy option
                if ($buy_result->num_rows == 0) {
                    echo "<td><form action='buy_game.php' method='post'><input type='hidden' name='game_id' value='$game_id'><input type='submit' value='Buy'></form></td>";
                } else {
                    echo "<td>Owned</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No games</td></tr>";
        }
        ?>
    </table>
    <a href="../user/dashboard.php">Back</a>
</body>
</html>


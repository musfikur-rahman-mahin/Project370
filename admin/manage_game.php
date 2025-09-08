<?php
session_start();
require '../connect.php';
// Check if user is logged in
if (!isset($_SESSION['admin_id'])|| $_SESSION['type'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

// List games
$sql_query = "SELECT * FROM Game";
$result = $conn->query($sql_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Games</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Manage Games</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Rating</th>
            <th>Price</th>
            <th>Space</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['rating'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['space'] . "</td>";
                echo "<td><form action='delete_game.php' method='post'>
                    <input type='hidden' name='game_id' value= $row[game_id]>
                    <input type='submit' name='action' value='Delete'></form></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No games</td></tr>";
        }
        ?>
    </table>
    
    <h2>Add Game</h2>
    <form action="add_game.php" method="post">
        Name: <input type="text" name="name" required><br>
        Description: <textarea name="description" required></textarea><br>
        Price: <input type="number" step="1" name="price" required><br>
        Space: <input type="number" name="space" required><br>
        <input type="submit" value="Add">
    </form>
    <a href="admin_dashboard.php">Back</a>
</body>
</html>
 ?>
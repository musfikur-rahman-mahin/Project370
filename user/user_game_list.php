<?php
session_start();
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'user') {
    header("Location: index.php");
}
include '../connect.php';

$u_id = $_SESSION['u_id'];

// Get owned games from user_game_list and game table 
$sql_query = "SELECT g.name, g.game_id, g.description, g.rating, l.user_rating FROM user_game_list l Join game g on l.game_id = g.game_id WHERE l.u_id = $u_id";
$result = $conn->query($sql_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Games</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>My Games</h1>
    <table>
        <tr><th>Name</th><th>Description</th><th>Rating</th><th>Action</th></tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                if ($row['user_rating'] == 0){
                    echo "<td><form action='rate_user_game.php' method='post'>
                        <input type='hidden' name='game_id' value= $row[game_id]>
                        <input type='submit' name='action' value='Rate'></form></td>";
                } else {
                    echo "<td>" . $row['rating'] . "</td>";
                }
                echo "<td><form action='delete_user_game.php' method='post'>
                    <input type='hidden' name='game_id' value= $row[game_id]>
                    <input type='submit' name='action' value='Delete'></form></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No games owned</td></tr>";
        }

        ?>
    </table>
    <a href="dashboard.php">Back</a>
</body>
</html>


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
<?php
session_start();
require '../connect.php';
// Check if user is logged in
if (!isset($_SESSION['admin_id'])|| $_SESSION['type'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// List users 
$sql_query = "SELECT u_id, name, flag FROM user";
// sql query
$result = $conn->query($sql_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Manage Users</h1>
    <table>
        <tr><th>Name</th><th>Banned?</th><th>Action</th></tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $u_id = $row['u_id'];
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . ($row['flag'] ? 'Yes' : 'No') . "</td>";
                if (!$row['flag']) {
                    echo "<td><form action='ban_user.php' method='post'>
                    <input type='hidden' name='u_id' value='$u_id'>
                    <input type='submit' name='action' value='Ban'></form></td>";
                } else {
                    echo "<td><form action='ban_user.php' method='post'>
                    <input type='hidden' name='u_id' value='$u_id'>
                    <input type='submit' name='action' value='Unban'></form></td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No users</td></tr>";
        }
        ?>
    </table>
    <a href="admin_dashboard.php">Back</a>
</body>
</html>

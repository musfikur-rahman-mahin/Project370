<?php
session_start();
require '../connect.php';
// Check if user is logged in
if (!isset($_SESSION['admin_id'])|| $_SESSION['type'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <nav>
        <a href="manage_user.php">Manage Users</a> |
        <a href="manage_game.php">Manage Games</a> |
        <a href="manage_threads.php">Manage Threads</a> |
        <a href="manage_pending.php" class="nav-link">Manage Pending</a> |
        <a href="../logout.php">Logout</a>
    </nav>
</body>
</html>


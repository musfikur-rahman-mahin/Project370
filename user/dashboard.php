<?php
include '../connect.php';

session_start();
if (!isset($_SESSION['u_id']) || $_SESSION['type'] != 'user') {
    header("Location: index.php");  // Redirect if not logged in as user
}


$u_id = $_SESSION['u_id'];

// Get user coins 
$sql = "SELECT coin, name FROM user WHERE u_id=$u_id";
// sql query
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$coins = $row['coin'];
$name = $row['name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Dashboard</h1>
    <p>User name: <?php echo $name;?></p>
    <p>coins: <?php echo $coins; ?></p>
    <nav >
        <a href="../game/gallery.php">View Games</a> |
        <a href="user_game_list.php">My Games</a> |
        <a href="../thread/threads.php">View Threads</a> |
        <a href="../thread/create_thread.php">Create Thread</a> |
        <a href="../transaction/add_coin.php">Add Coins</a> |
        <a href="../marketplace/marketplace.php">Marketplace</a> |
        <a href="../logout.php">Logout</a>
    </nav>
</body>
</html>


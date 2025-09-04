<?php
session_start();
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'user') {
    header("Location: index.php");
}
include '../connect.php';

$u_id = $_SESSION['u_id'];
$amount = $_POST['amount'];
$method = $_POST['method'];

// Insert transaction
$trans_sql = "INSERT INTO Transaction (amount, u_id) VALUES ($amount, $u_id)";
if ($conn->query($trans_sql) === TRUE) {
    $tr_id = $conn->insert_id;  // Get new tr_id
    
    // Insert method
    $method_sql = "INSERT INTO Transaction_Method (tr_id, method) VALUES ($tr_id, '$method')";
    $conn->query($method_sql);
    
    // Add to coins
    $update_sql = "UPDATE user SET coin = coin + $amount WHERE u_id = $u_id";
    $conn->query($update_sql);
    
    echo "<p class='success'>Coins added! <a href='user_dashboard.php'>Back</a></p>";
} else {
    echo "<p class='error'>Error: " . $conn->error . "</p>";
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Coins</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Add Coins</h1>
    <form method="post">
        Amount: <input type="number" name="amount" required><br>
        Method: <select name="method">
            <option value="credit_card">Credit Card</option>
            <option value="bkash">Bkash</option>
        </select><br>
        <input type="submit" value="Add">
    </form>
    <a href="../user/dashboard.php">Back</a>
</body>
</html>
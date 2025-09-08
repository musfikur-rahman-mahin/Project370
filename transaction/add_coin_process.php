<?php
session_start();
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'user') {
    header("Location: ../user/index.php");
}
include '../connect.php';

$u_id = $_SESSION['u_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = (int)$_POST['amount'];
    $method = $_POST['method'];


    // transaction table 
    $sql_query = "INSERT INTO Transaction (amount, u_id) VALUES ($amount, $u_id )";
    if ($conn->query($sql_query) == TRUE) {
        $tr_id = $conn->insert_id;  // Get new tr_id (auto incremented)
        
        // method table 
        $sql_query = "INSERT INTO Transaction_Method (tr_id, method) VALUES ($tr_id, '$method')";
        $conn->query($sql_query);
        
        // Add to coins 
        $sql_query = "UPDATE user SET coin = coin + $amount WHERE u_id = $u_id";
        $conn->query($sql_query);
        
        echo "<p class='success'>Coins added! <a href='../user/dashboard.php'>Back</a></p>";
    } else {
        echo "<p class='error'>Error: " . $conn->error . "</p>";
    }
}
?>


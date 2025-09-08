<?php
session_start();
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'user') {
    header("Location: index.php");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (int)$_POST['amount'];
    $method = $_POST['method'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Payment Details</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Enter Payment Details</h2>
    <form action="add_coin_process.php" method="post">
        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <input type="hidden" name="method" value="<?php echo $method; ?>">
        <?php
        if ($method == "credit_card") {
            echo "Card Number: <input type='text' name='card_number' placeholder='1234-5678-9012-3456' required><br>";
            echo "Expiry Date: <input type='text' name='expiry_date' placeholder='MM/YYYY' required><br>";
        } elseif ($method == "bkash") {
            echo "Number: <input type='number' name='number' placeholder='01xxxxxxxx' required><br>";
        }
        ?>
        <input type="submit" value="Complete Transaction">
    </form>
    <a href="add_coin.php">Back</a>
</body>
</html>
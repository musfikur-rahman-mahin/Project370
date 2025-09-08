<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Coins</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Add Coins</h1>
    <form action="method.php" method="post">
        Amount: 
        <select name="amount" required>
            <option value="10">10 Coins</option>
            <option value="20">20 Coins</option>
            <option value="50">50 Coins</option>
            <option value="100">100 Coins</option>
        </select><br>
        Method: 
        <select name="method">
            <option value="credit_card">Credit Card</option>
            <option value="bkash">Bkash</option>
        </select><br>
        <input type="submit" value="Add">
    </form>
    <a href="../user/dashboard.php">Back</a>
</body>
</html>
<?php
require_once '../connect.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['u_id'])) {
    header("Location: ../login.php");
    exit();
}

$u_id = $_SESSION['u_id'];
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_name = trim($_POST['game_name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $game_user_id = trim($_POST['game_user_id']);
    $game_password = trim($_POST['game_password']);

    if ($game_name && $price > 0 && $description !== '' && $game_user_id !== '' && $game_password !== '') {
        // Insert into Used_Game_Marketplace with admin_status 'PENDING'
        $stmt = $conn->prepare("INSERT INTO Used_Game_Marketplace (u_id, game_name, price, description, game_user_id, game_password, admin_status) VALUES (?, ?, ?, ?, ?, ?, 'PENDING')");
        $stmt->bind_param("isdsss", $u_id, $game_name, $price, $description, $game_user_id, $game_password);
        $stmt->execute();
        $stmt->close();
        $message = "Game enlistment request sent to admin for approval.";
    } else {
        $message = "Please fill all fields correctly.";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Enlist Your Game</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);}
        h2 { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; }
        select, input[type="number"], input[type="text"], textarea { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        .message { margin-bottom: 15px; color: green; }
        .error { margin-bottom: 15px; color: red; }
        .back-link { display: block; margin-top: 15px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enlist Your Game for Sale</h2>
        <?php if ($message): ?>
            <div class="<?php echo strpos($message, 'success') !== false ? 'message' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <label for="game_name">Game Name:</label>
            <input type="text" name="game_name" id="game_name" required>
            <label for="price">Price:</label>
            <input type="number" step="1" min="0" name="price" id="price" required>
            <label for="game_user_id">Game User ID:</label>
            <input type="text" name="game_user_id" id="game_user_id" required>
            <label for="game_password">Game Password:</label>
            <input type="text" name="game_password" id="game_password" required>
            <label for="description">Description (Unlocked items, levels, etc.):</label>
            <textarea name="description" id="description" required></textarea>
            <input type="submit" value="Enlist Game">
        </form>
        <a href="marketplace.php" class="back-link">Back to Marketplace</a>
        <a href="../user/dashboard.php" class="link-btn">Dashboard</a>
    </div>
</body>
</html>

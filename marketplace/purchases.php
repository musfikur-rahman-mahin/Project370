<?php
require_once '../connect.php';
session_start();

$is_admin = isset($_SESSION['type']) && $_SESSION['type'] === 'admin';

// Handle hide request
if ($is_admin && isset($_GET['hide_market_id'])) {
    $market_id = intval($_GET['hide_market_id']);
    $stmt = $conn->prepare("UPDATE Used_Game_Marketplace SET admin_flag = TRUE WHERE market_id = ?");
    $stmt->bind_param("i", $market_id);
    $stmt->execute();
    $stmt->close();
}

// Handle buy request
if (isset($_POST['buy_market_id']) && isset($_SESSION['u_id']) && !$is_admin) {
    $market_id = intval($_POST['buy_market_id']);
    $buyer_id = $_SESSION['u_id'];
    $stmt = $conn->prepare("UPDATE Used_Game_Marketplace SET buyer_id = ? WHERE market_id = ?");
    $stmt->bind_param("ii", $buyer_id, $market_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all available used games not hidden by admin

$sql = "SELECT m.market_id, m.game_name, m.description, m.game_user_id, m.game_password
    FROM Used_Game_Marketplace m
    WHERE m.buyer_id = ? AND m.admin_status = 'APPROVED' AND m.status = 'SOLD'
    ORDER BY m.listed_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $_SESSION['u_id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Marketplace</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .market-item { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 6px; }
        .market-title { font-size: 1.2em; font-weight: bold; }
        .market-meta { color: #555; margin-bottom: 8px; }
        .market-desc { margin-bottom: 8px; }
        .cred { background: #f8f9fa; padding: 8px; border-radius: 4px; margin-bottom: 8px; }
        .hide-btn { background: #dc3545; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .buy-btn { background: #28a745; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .link-btn { background: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; text-decoration: none; margin-right: 10px; display: inline-block; font-size: 1em; transition: background 0.2s; }
        .link-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h2>Purchased Used Games</h2>
    <a href="marketplace.php" class="link-btn">Back to Marketplace</a>
    <a href="../user/dashboard.php" class="link-btn">Dashboard</a>
    <hr>
    <?php
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="market-item">';
            echo '<div class="market-title">' . htmlspecialchars($row['game_name']) . '</div>';
            echo '<div class="market-desc"><strong>Description:</strong> ' . htmlspecialchars($row['description']) . '</div>';
            echo '<div class="cred"><strong>Game User ID:</strong> ' . htmlspecialchars($row['game_user_id']) . '<br>';
            echo '<strong>Game Password:</strong> ' . htmlspecialchars($row['game_password']) . '</div>';
            echo '</div>';
        }
    } else {
        echo "<p>No games.</p>";
    }
    $conn->close();
    ?>
</body>
</html>
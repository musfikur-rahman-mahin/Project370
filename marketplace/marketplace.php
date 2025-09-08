<?php
require_once '../connect.php';
session_start();


// Show user's coin balance
$user_coin = null;
if (isset($_SESSION['u_id'])) {
    $coin_sql = "SELECT coin FROM user WHERE u_id = ?";
    $coin_stmt = $conn->prepare($coin_sql);
    $coin_stmt->bind_param("i", $_SESSION['u_id']);
    $coin_stmt->execute();
    $coin_stmt->bind_result($user_coin);
    $coin_stmt->fetch();
    $coin_stmt->close();
}

// Handle buy request
if (isset($_POST['buy_market_id']) && isset($_SESSION['u_id'])) {
    $market_id = intval($_POST['buy_market_id']);
    $buyer_id = $_SESSION['u_id'];

    // Check if already bought
    $check_sql = "SELECT status, buyer_id FROM Used_Game_Marketplace WHERE market_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $market_id);
    $check_stmt->execute();
    $check_stmt->bind_result($current_status, $current_buyer);
    $check_stmt->fetch();
    $check_stmt->close();
    if ($current_status === 'SOLD' || $current_buyer) {
        $buy_message = "This game has already been sold.";
    } else {
        // Fetch user's coin balance
        $coin_sql = "SELECT coin FROM user WHERE u_id = ?";
        $coin_stmt = $conn->prepare($coin_sql);
        $coin_stmt->bind_param("i", $buyer_id);
        $coin_stmt->execute();
        $coin_stmt->bind_result($user_coin);
        $coin_stmt->fetch();
        $coin_stmt->close();

        // Fetch game price and seller id
        $price_sql = "SELECT price, u_id FROM Used_Game_Marketplace WHERE market_id = ?";
        $price_stmt = $conn->prepare($price_sql);
        $price_stmt->bind_param("i", $market_id);
        $price_stmt->execute();
        $price_stmt->bind_result($game_price, $seller_id);
        $price_stmt->fetch();
        $price_stmt->close();

        if ($user_coin >= $game_price) {
            // Deduct coins from buyer
            $update_coin_sql = "UPDATE user SET coin = coin - ? WHERE u_id = ?";
            $update_coin_stmt = $conn->prepare($update_coin_sql);
            $update_coin_stmt->bind_param("di", $game_price, $buyer_id);
            $update_coin_stmt->execute();
            $update_coin_stmt->close();

            // Add coins to seller
            $add_coin_sql = "UPDATE user SET coin = coin + ? WHERE u_id = ?";
            $add_coin_stmt = $conn->prepare($add_coin_sql);
            $add_coin_stmt->bind_param("di", $game_price, $seller_id);
            $add_coin_stmt->execute();
            $add_coin_stmt->close();

            // Mark as sold and set buyer
            $stmt = $conn->prepare("UPDATE Used_Game_Marketplace SET buyer_id = ?, status = 'SOLD' WHERE market_id = ?");
            $stmt->bind_param("ii", $buyer_id, $market_id);
            $stmt->execute();
            $stmt->close();

            $buy_message = "Purchase successful!";
        } else {
            $buy_message = "You do not have enough coins to buy this game.";
        }
    }
}

// Delete game from marketplace
if (isset($_POST['delete_market_id']) && isset($_SESSION['u_id'])) {
    $market_id = intval($_POST['delete_market_id']);
    $user_id = $_SESSION['u_id'];
    // Only allow delete if the user is the seller
    $stmt = $conn->prepare("DELETE FROM Used_Game_Marketplace WHERE market_id = ? AND u_id = ?");
    $stmt->bind_param("ii", $market_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all available games for users
$user_id = isset($_SESSION['u_id']) ? $_SESSION['u_id'] : null;
if ($user_id) {
    $sql = "SELECT m.market_id, m.price, m.description, m.listed_at, u.name AS seller, m.game_name, m.admin_flag, m.buyer_id, m.u_id, m.admin_status, m.status
            FROM Used_Game_Marketplace m
            JOIN user u ON m.u_id = u.u_id
            WHERE (
                (m.admin_status = 'APPROVED' AND m.status = 'AVAILABLE' AND m.admin_flag = FALSE AND (m.buyer_id IS NULL OR m.buyer_id != ?))
                OR
                (m.admin_status = 'PENDING' AND m.u_id = ?)
            )
            ORDER BY m.listed_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If not logged in, only show APPROVED and AVAILABLE games
    $sql = "SELECT m.market_id, m.price, m.description, m.listed_at, u.name AS seller, m.game_name, m.admin_flag, m.buyer_id, m.u_id, m.admin_status, m.status
            FROM Used_Game_Marketplace m
            JOIN user u ON m.u_id = u.u_id
            WHERE m.admin_status = 'APPROVED' AND m.status = 'AVAILABLE' AND m.admin_flag = FALSE
            ORDER BY m.listed_at DESC";
    $result = $conn->query($sql);
}
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
        .hide-btn { background: #dc3545; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .buy-btn { background: #28a745; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .link-btn { background: #007bff; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; text-decoration: none; margin-bottom: 20px; display: inline-block; }
        .coin-balance { font-size: 1.1em; color: #007bff; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>Marketplace - Used Games for Sale</h2>
    <?php if (isset($user_coin)): ?>
        <div class="coin-balance">Available Coins: <?php echo htmlspecialchars($user_coin); ?></div>
    <?php endif; ?>
    <a href="enlisted.php" class="link-btn">Enlist Your Game</a>
    <a href="purchases.php" class="link-btn">My Purchases</a>
    <a href="../user/dashboard.php" class="link-btn">Dashboard</a>
    <hr>
    <?php
    // Show message if set
    if (isset($buy_message)) {
        echo '<div style="color:red; font-weight:bold; margin-bottom:10px;">' . htmlspecialchars($buy_message) . '</div>';
    }

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="market-item">';
            echo '<div class="market-title">' . htmlspecialchars($row['game_name']) . '</div>';
            echo '<div class="market-meta"><strong>Price:</strong> ' . htmlspecialchars($row['price']) . ' coins</div>';
            echo '<div class="market-desc"><strong>Description:</strong> ' . htmlspecialchars($row['description']) . '</div>';
            echo '<div class="market-meta"><strong>Enlisted at:</strong> ' . htmlspecialchars($row['listed_at']) . '</div>';
            echo '<div class="market-meta"><strong>Posted by:</strong> ' . htmlspecialchars($row['seller']) . '</div>';
            // If the post is pending and belongs to the logged-in user, show delete button and status
            if ($row['admin_status'] === 'PENDING' && isset($_SESSION['u_id']) && $_SESSION['u_id'] == $row['u_id']) {
                echo '<div class="market-meta" style="color:orange;"><strong>Status:</strong> Pending Approval</div>';
                echo '<form method="post" style="display:inline;">
                        <input type="hidden" name="delete_market_id" value="' . $row['market_id'] . '">
                        <button type="submit" class="hide-btn">Delete</button>
                      </form>';
            } elseif ($row['admin_status'] === 'APPROVED' && $row['status'] === 'AVAILABLE') {
                // Approved and available: show to all users
                if (isset($_SESSION['u_id']) && $_SESSION['u_id'] == $row['u_id']) {
                    echo '<form method="post" style="display:inline;">
                            <input type="hidden" name="delete_market_id" value="' . $row['market_id'] . '">
                            <button type="submit" class="hide-btn">Delete</button>
                          </form>';
                } else {
                    echo '<form method="post" style="display:inline;">
                            <input type="hidden" name="buy_market_id" value="' . $row['market_id'] . '">
                            <button type="submit" class="buy-btn">Buy</button>
                          </form>';
                }
            } elseif ($row['admin_status'] === 'APPROVED' && $row['status'] === 'SOLD' && isset($_SESSION['u_id']) && $_SESSION['u_id'] == $row['buyer_id']) {
                // Approved and sold: show only to buyer
                echo '<div class="market-meta" style="color:green;"><strong>Status:</strong> Sold (You bought this game)</div>';
            }
            echo '</div>';
        }
    } else {
        echo "<p>No games available for sale.</p>";
    }
    $conn->close();
    ?>
</body>
</html>
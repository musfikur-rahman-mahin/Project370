<?php
require_once '../connect.php';
session_start();

// Only allow admin
if (!isset($_SESSION['type']) || $_SESSION['type'] !== 'admin') {
    header('Location: ../admin/admin_login.php');
    exit();
}

// Approve pending game
if (isset($_POST['approve_market_id'])) {
    $market_id = intval($_POST['approve_market_id']);
    $stmt = $conn->prepare("UPDATE Used_Game_Marketplace SET admin_status = 'APPROVED' WHERE market_id = ?");
    $stmt->bind_param("i", $market_id);
    $stmt->execute();
    $stmt->close();
}

// Disapprove pending game
if (isset($_POST['disapprove_market_id'])) {
    $market_id = intval($_POST['disapprove_market_id']);
    $stmt = $conn->prepare("DELETE FROM Used_Game_Marketplace WHERE market_id = ?");
    $stmt->bind_param("i", $market_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all pending games
$sql = "SELECT m.market_id, m.game_name, m.price, m.description, m.listed_at, u.name AS seller FROM Used_Game_Marketplace m JOIN user u ON m.u_id = u.u_id WHERE m.admin_status = 'PENDING' AND m.buyer_id IS NULL AND m.u_id IS NOT NULL ORDER BY m.listed_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Pending Games</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fa; margin: 0; }
        .dashboard-container { max-width: 800px; margin: 40px auto; background: #fff; padding: 32px 40px; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        h2 { margin-bottom: 24px; color: #007bff; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #fafafa; }
        .action-btn, .nav-link { background: #007bff; color: #fff; border: none; padding: 8px 18px; border-radius: 6px; text-decoration: none; margin: 0 8px 12px 0; display: inline-block; font-size: 1em; transition: background 0.2s; cursor: pointer; }
        .action-btn:hover, .nav-link:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Pending Game Enlistments</h2>
        <table>
            <tr><th>Game Name</th><th>Seller</th><th>Price</th><th>Description</th><th>Enlisted At</th><th>Action</th></tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['game_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['seller']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['listed_at']) . "</td>";
                    echo "<td>";
                    echo "<form method='post' style='display:inline;'>
                            <input type='hidden' name='approve_market_id' value='" . $row['market_id'] . "'>
                            <input type='submit' value='Approve' class='action-btn'>
                          </form>";
                    echo "<form method='post' style='display:inline;'>
                            <input type='hidden' name='disapprove_market_id' value='" . $row['market_id'] . "'>
                            <input type='submit' value='Disapprove' class='action-btn'>
                          </form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No pending games.</td></tr>";
            }
            ?>
        </table>
        <a href="admin_dashboard.php" class="nav-link">Back to Dashboard</a>
    </div>
</body>
</html>

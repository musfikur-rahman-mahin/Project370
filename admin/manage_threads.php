<?php
// filepath: e:\xampp\htdocs\Project370-main\admin\manage_threads.php
require_once '../connect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['type']) || $_SESSION['type'] !== 'admin') {
    header("Location: ../admin/admin_login.php");
    exit();
}

// Handle hide/show actions
if (isset($_GET['action']) && isset($_GET['thread_id'])) {
    $thread_id = intval($_GET['thread_id']);
    if ($_GET['action'] === 'hide') {
        $stmt = $conn->prepare("UPDATE Thread SET flag = TRUE WHERE thread_id = ?");
        $stmt->bind_param("i", $thread_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($_GET['action'] === 'show') {
        $stmt = $conn->prepare("UPDATE Thread SET flag = FALSE WHERE thread_id = ?");
        $stmt->bind_param("i", $thread_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all threads (including hidden)
$sql = "SELECT t.thread_id, t.title, u.name AS username, t.flag
        FROM Thread t
        JOIN user u ON t.u_id = u.u_id
        ORDER BY t.thread_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Threads</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);}
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f0f0f0; }
        .hide-btn { background: #dc3545; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .show-btn { background: #28a745; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .hidden-thread { background: #ffe5e5; }
        .comment-link { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Threads</h2>
        <table>
            <tr>
                <th>Thread ID</th>
                <th>Title</th>
                <th>Username</th>
                <th>Status</th>
                <th>Comments</th>
                <th>Action</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="<?php echo $row['flag'] ? 'hidden-thread' : ''; ?>">
                        <td><?php echo $row['thread_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo $row['flag'] ? 'Hidden' : 'Visible'; ?></td>
                        <td>
                            <a href="../thread/comments.php?thread_id=<?php echo $row['thread_id']; ?>" class="comment-link">View Comments</a>
                        </td>
                        <td>
                            <?php if ($row['flag']): ?>
                                <a href="?action=show&thread_id=<?php echo $row['thread_id']; ?>" class="show-btn">Show</a>
                            <?php else: ?>
                                <a href="?action=hide&thread_id=<?php echo $row['thread_id']; ?>" class="hide-btn">Hide</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No threads found.</td></tr>
            <?php endif; ?>
        </table>
        <a href="../admin/admin_dashboard.php">Back to Admin Dashboard</a>
    </div>
</body>
</html>
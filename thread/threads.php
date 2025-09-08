<?php
require_once '../connect.php';

// Fetch threads with user name, title, and time using Participate, Thread, and user tables
$sql = "SELECT t.thread_id, t.title, u.name AS username, t.created_at
        FROM Participate p
        JOIN Thread t ON p.thread_id = t.thread_id
        JOIN user u ON p.u_id = u.u_id
        WHERE t.flag = FALSE
        ORDER BY t.created_at DESC"; // Show most recent threads first
$result = $conn->query($sql);

$threads = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $threads[] = $row;
    }
}
$total_threads = count($threads);
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Threads</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #f5f5f5;
        }
        .headline { font-size: 2em; }
        .create-btn {
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .thread-list { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f0f0f0; }
        .thread-count { margin-bottom: 15px; font-size: 1.1em; color: #555; }
        .no-thread { color: #888; font-style: italic; padding: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="headline">Threads</div>
        <a href="create_thread.php" class="create-btn">Create Thread</a>
        <a href="../user/dashboard.php" class="create-btn">Back to Dashboard</a>
    </div>
    <div class="thread-list">
        <div class="thread-count">
            Total threads: <?php echo $total_threads; ?>
        </div>
        <table>
            <tr>
                <th>Serial</th>
                <th>Username</th>
                <th>Title</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
            <?php if (empty($threads)): ?>
                <tr>
                    <td colspan="5" class="no-thread">No thread</td>
                </tr>
            <?php else: ?>
                <?php $serial = 1; ?>
                <?php foreach ($threads as $thread): ?>
                    <tr>
                        <td><?php echo $serial++; ?></td>
                        <td><?php echo htmlspecialchars($thread['username']); ?></td>
                        <td><?php echo htmlspecialchars($thread['title']); ?></td>
                        <td>
                            <?php echo isset($thread['created_at']) ? htmlspecialchars($thread['created_at']) : 'N/A'; ?>
                        </td>
                        <td>
                            <a href="comments.php?thread_id=<?php echo $thread['thread_id']; ?>" class="create-btn" style="background:#28a745;">Comments</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
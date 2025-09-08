<?php
require_once '../connect.php';
session_start();

$thread_id = isset($_GET['thread_id']) ? intval($_GET['thread_id']) : 0;

// Fetch thread title
$thread_title = '';
$stmt = $conn->prepare("SELECT title FROM Thread WHERE thread_id = ?");
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$stmt->bind_result($thread_title);
$stmt->fetch();
$stmt->close();

// Handle new comment submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    $u_id = isset($_SESSION['u_id']) ? $_SESSION['u_id'] : null;
    if ($comment !== '' && $u_id) {
        $stmt = $conn->prepare("INSERT INTO Thread_Comments (thread_id, comments, u_id) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $thread_id, $comment, $u_id);
        if ($stmt->execute()) {
            $message = "Comment posted!";
        } else {
            $message = "Failed to post comment.";
        }
        $stmt->close();
    }
}

// Fetch comments for this thread with username and time
$comments = [];
$sql = "SELECT tc.comments, tc.created_at, u.name AS username
        FROM Thread_Comments tc
        LEFT JOIN user u ON tc.u_id = u.u_id
        WHERE tc.thread_id = ?
        ORDER BY tc.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $thread_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Comments for Thread</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);}
        h2 { margin-bottom: 20px; }
        .comment { padding: 10px; border-bottom: 1px solid #eee; }
        .comment-meta { font-size: 0.95em; color: #555; margin-bottom: 4px; }
        .no-comment { color: #888; font-style: italic; }
        .back-link { display: block; margin-top: 15px; color: #007bff; text-decoration: none; }
        .message { margin-bottom: 15px; color: green; }
        .error { margin-bottom: 15px; color: red; }
        .comment-form { margin-bottom: 25px; }
        textarea { width: 100%; height: 60px; padding: 8px; border-radius: 4px; border: 1px solid #ccc; margin-bottom: 10px; }
        input[type="submit"] { background: #007bff; color: #fff; border: none; padding: 8px 18px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Comments for: <?php echo htmlspecialchars($thread_title); ?></h2>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="post" class="comment-form">
            <label for="comment">Add a comment:</label>
            <textarea name="comment" id="comment" required></textarea>
            <input type="submit" value="Post Comment">
        </form>
        <?php if (empty($comments)): ?>
            <div class="no-comment">No comments yet.</div>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <div class="comment-meta">
                        <?php echo htmlspecialchars($comment['username'] ?? 'Unknown'); ?> |
                        <?php echo htmlspecialchars($comment['created_at'] ?? ''); ?>
                    </div>
                    <?php echo htmlspecialchars($comment['comments']); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php
        // Back link with GET parameter
        $back_url = (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') ? '../admin/manage_threads.php' : 'threads.php';
        ?>
        <a href="<?php echo $back_url; ?>" class="back-link">Back to Threads</a>
    </div>
</body>
</html>
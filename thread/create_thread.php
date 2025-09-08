<?php
session_start();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../connect.php'; // Adjust path if needed

    // Fetch u_id from session (user must be logged in)
    if (!isset($_SESSION['u_id'])) {
        $message = "You must be logged in to create a thread.";
    } else {
        $title = trim($_POST['title']);
        $u_id = $_SESSION['u_id'];

        if ($title !== '') {
            // Insert into Thread table
            $stmt = $conn->prepare("INSERT INTO Thread (title, u_id) VALUES (?, ?)");
            $stmt->bind_param("si", $title, $u_id);
            if ($stmt->execute()) {
                $thread_id = $stmt->insert_id;
                // Insert into Participate table
                $stmt2 = $conn->prepare("INSERT INTO Participate (u_id, thread_id) VALUES (?, ?)");
                $stmt2->bind_param("ii", $u_id, $thread_id);
                $stmt2->execute();
                $stmt2->close();
                $message = "Thread created successfully!";
            } else {
                $message = "Error creating thread.";
            }
            $stmt->close();
        } else {
            $message = "Thread title cannot be empty.";
        }
        $conn->close();
      }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Thread</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; }
        .container { max-width: 400px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);}
        h2 { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; }
        input[type="text"] { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        .back-link { display: block; margin-top: 15px; color: #007bff; text-decoration: none; }
        .message { margin-bottom: 15px; color: green; }
        .error { margin-bottom: 15px; color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create a Thread:</h2>
        <?php if ($message): ?>
            <div class="<?php echo strpos($message, 'success') !== false ? 'message' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <label for="title">Thread Title:</label>
            <input type="text" id="title" name="title" required>
            <input type="submit" value="Create Thread">
        </form>
        <a href="threads.php" class="back-link">Back to
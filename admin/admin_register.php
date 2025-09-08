<?php
include "../connect.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $conn->real_escape_string($_POST['admin_id']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // check if admin id exists
    $sql_query = "SELECT * FROM admin WHERE admin_id='$admin_id'";
    $result = $conn->query($sql_query);

    if ($check_result->num_rows > 0) {
        $message = "Admin ID already exists!";
    } else {
        $sql_query = "INSERT INTO admin (admin_id, pass) VALUES ('$admin_id', '$password')";
        if ($conn->query($sql_query) === TRUE) {
            $message = "New admin account created successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { max-width: 400px; margin: auto; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px; width: 100%; }
        .message { text-align: center; color: green; margin: 10px 0; }
        .error { color: red; }
    </style>
</head>
<body>

<h2>Create Admin Account</h2>

<?php if($message != ""): ?>
    <div class="message <?= strpos($message, 'Error') !== false || strpos($message, 'exists') !== false ? 'error' : '' ?>">
        <?= $message ?>
    </div>
<?php endif; ?>

<form method="POST">
    <label for="admin_id">Admin ID:</label>
    <input type="text" id="admin_id" name="admin_id" required>

    <label for="password">Password:</label>
    <input type="text" id="password" name="password" required>

    <button type="submit">Create Admin</button>
</form>

</body>
</html>

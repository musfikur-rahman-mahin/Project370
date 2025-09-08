<?php

session_start();
include "../connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $admin_id = $conn->real_escape_string($_POST['admin_id']);
    $pass = $_POST['password'];
    
    // fetching the password with matching email
    $sql_check = "SELECT pass FROM admin WHERE admin_id = '$admin_id'";
    // sql query
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // turning the result into a row
        $row = $result->fetch_assoc();

        // verify entered password
        if (password_verify($pass, $row['pass'])) {

            $_SESSION['admin_id'] = $admin_id; // Save admin login            
            $_SESSION['type'] = 'admin';
            // redirect to a dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No admin found with that id.";
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joystick Junkyard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <form  method="POST">  
            <div class="form-group">
                <label for="admin_id">ID:</label>
                <input type="text" id="admin_id" name="admin_id" required>
            </div>

            <div class="form-group password-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <div class="show-password-container">
                    <input type="checkbox" id="show-password">
                    <label for="show-password">Show Password</label>
                </div>
            </div>

            <button type="submit">Login</button> 
        </form>
    </div>
</body>
</html>



<script>
    const passwordInput = document.getElementById('password');
    const showPassword = document.getElementById('show-password');

    showPassword.addEventListener('change', function() {
        if (this.checked) {
            passwordInput.type = 'text'; // Show password
        } else {
            passwordInput.type = 'password'; // Hide password
        }
    });
</script>

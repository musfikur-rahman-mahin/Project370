<?php

session_start();
include "../connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    // fetching the password with matching email
    $sql_check = "SELECT pass, u_id, flag FROM user WHERE email = '$email'";
    // sql query
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // turning the result into a row
        $row = $result->fetch_assoc();

        // verify 
        if ($row['flag'] == 0){
            if (password_verify($password, $row['pass'])) {

                $_SESSION['u_id'] = $row['u_id']; // Save user login
                $_SESSION['type'] = 'user';            
                // redirect to a dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "You are banned currently! Please try later.";
        }
    } else {
        echo "No user found with that email.";
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
    <?php

    if (isset($_SESSION['success_message'])) {
        echo "<p style='color: green; text-align: center;'>".$_SESSION['success_message']."</p>";
        unset($_SESSION['success_message']); // Remove it so it shows only once
    }
    ?>

    <div class="login-container">
        <h1>Login to Junkyard</h1>
        <form method="POST">            

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" required>
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
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p><a href = "../admin/admin_login.php">Admin login</a></p>
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



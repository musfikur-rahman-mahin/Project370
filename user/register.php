

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
        <h1>Register to Junkyard</h1>
        <form method="POST">

            <div class="form-group">
                <label for="user_name">User Name:</label>
                <input type="text" id="user_name" name="user_name" required>
            </div>

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

            <button type="submit">Register</button>

        </form>

        <p>Already have an account? <a href="index.php">Login here</a></p>

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


<?php

session_start();
include "../connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $user_name = $conn->real_escape_string($_POST['user_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hasing the password with deafult algorithm

    //checking if the email exists
    $sql_check = "SELECT * FROM user WHERE email='$email'";
    // sql query
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) 
    {
        header("Location: index.php");
    }
    else
    {
        // insert new user
        $sql_insert = "INSERT INTO user (name, email, pass) VALUES ('$user_name', '$email', '$password')";
        // sql query    
        if ($conn->query($sql_insert) == TRUE) {
            // a message to show in login page
            $_SESSION['success_message'] = "Registration successful! Please login.";
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }

        // Close the connection
        $conn->close();
    }

}

?>

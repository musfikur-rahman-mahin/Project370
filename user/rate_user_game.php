<?php
session_start();
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'user') {
    header("Location: index.php");
}
include '../connect.php';

$game_id = $_POST['game_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Games</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php
        echo "<form action='rating_process.php' method='post'>
            <input type='hidden' name='game_id' value= '$game_id'>
            <label><input type='radio' name='value' value='1' required> 1</label>
            <label><input type='radio' name='value' value='2'> 2</label>
            <label><input type='radio' name='value' value='3'> 3</label>
            <label><input type='radio' name='value' value='4'> 4</label>
            <label><input type='radio' name='value' value='5'> 5</label>
            <br><button type='submit'>Submit</button>
        </form>";
    ?>
</body>
</html>

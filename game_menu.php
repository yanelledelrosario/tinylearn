<?php
session_start();

if (!isset($_SESSION['child_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h1>Hi <?php echo $_SESSION['child_name']; ?>! 🎉</h1>
    <h3>Choose a Game</h3>

    <button onclick="location.href='games/animal.php'">🐶 Animal Sounds</button><br><br>
    <button onclick="location.href='games/colors.php'">🎨 Color Game</button><br><br>
    <button onclick="location.href='games/counting.php'">🔢 Counting Game</button><br><br>
    <button onclick="location.href='games/shapes.php'">🧩 Shape Match</button><br><br>

    <button onclick="location.href='dashboard.php'">Back</button>
</div>
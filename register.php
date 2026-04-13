<?php
session_start();
include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO parents (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Email already exists.";
    }
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h1>Register</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create Account</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>
<?php
session_start();
include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM parents WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['parent_id'] = $user['id'];
            $_SESSION['parent_name'] = $user['full_name'];

            header("Location: dashboard.php");
            exit();
        }
    }

    $error = "Invalid email or password.";
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h1>Login</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <p>No account? <a href="register.php">Register</a></p>
</div>
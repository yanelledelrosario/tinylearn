<?php
session_start();
include "includes/db.php";

if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $age = $_POST['age'];
    $parent_id = $_SESSION['parent_id'];

    $stmt = $conn->prepare("INSERT INTO children (parent_id, name, age) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $parent_id, $name, $age);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h1>Add Child</h1>

    <form method="POST">
        <input type="text" name="name" placeholder="Child Name" required>
        <input type="number" name="age" placeholder="Age" min="1" max="10" required>
        <button type="submit">Save Child</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
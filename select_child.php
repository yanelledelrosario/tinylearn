<?php
session_start();
include "includes/db.php";

if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {

    $child_id = (int) $_GET['id'];
    $parent_id = $_SESSION['parent_id'];

    $stmt = $conn->prepare("SELECT * FROM children WHERE id = ? AND parent_id = ?");
    $stmt->bind_param("ii", $child_id, $parent_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $child = $result->fetch_assoc();

        $_SESSION['child_id'] = $child['id'];
        $_SESSION['child_name'] = $child['name'];

        header("Location: game_menu.php");
        exit();
    }
}

header("Location: dashboard.php");
exit();
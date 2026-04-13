<?php
session_start();
include "includes/db.php";

if (!isset($_SESSION['parent_id']) || !isset($_SESSION['child_id'])) {
    http_response_code(403);
    exit("Unauthorized");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $child_id  = (int) $_POST['child_id'];
    $game_type = $_POST['game_type'];
    $score     = (int) $_POST['score'];
    $stars     = (int) $_POST['stars'];

    // Verify child belongs to the logged-in parent
    $parent_id = (int) $_SESSION['parent_id'];
    $check = $conn->prepare("SELECT id FROM children WHERE id = ? AND parent_id = ?");
    $check->bind_param("ii", $child_id, $parent_id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        http_response_code(403);
        exit("Unauthorized");
    }

    $stmt = $conn->prepare("
        INSERT INTO game_progress (child_id, game_type, score, stars)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE score = ?, stars = ?
    ");
    $stmt->bind_param("isiiii", $child_id, $game_type, $score, $stars, $score, $stars);
    $stmt->execute();
}
?>
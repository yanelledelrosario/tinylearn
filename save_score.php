<?php
include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $child_id = $_POST['child_id'];
    $game_type = $_POST['game_type'];
    $score = $_POST['score'];
    $stars = $_POST['stars'];

    $stmt = $conn->prepare("
        INSERT INTO game_progress (child_id, game_type, score, stars)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE score = ?, stars = ?
    ");

    $stmt->bind_param("isiiii", 
        $child_id, 
        $game_type, 
        $score, 
        $stars, 
        $score, 
        $stars
    );

    $stmt->execute();
}
?>
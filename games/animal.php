<?php
session_start();
if (!isset($_SESSION['child_id'])) {
    header("Location: ../dashboard.php");
    exit();
}
?>

<link rel="stylesheet" href="../css/style.css">
<script src="../js/animal.js" defer></script>

<!-- ================= GAME CONTAINER ================= -->
<div class="game-container">

    <!-- LEFT PANEL -->
    <div class="left-panel">
        <h1>Animal Sound Game</h1>
        <h2 id="instruction">Listen carefully 👂</h2>
    </div>

    <!-- CENTER PANEL (ANIMALS) -->
    <div class="center-panel">
        <div id="animals">
            <button class="animal-btn" data-animal="cow">🐮</button>
            <button class="animal-btn" data-animal="dog">🐶</button>
            <button class="animal-btn" data-animal="cat">🐱</button>
            <button class="animal-btn" data-animal="duck">🦆</button>
            <button class="animal-btn" data-animal="sheep">🐑</button>
            <button class="animal-btn" data-animal="lion">🦁</button>
            <button class="animal-btn" data-animal="elephant">🐘</button>
            <button class="animal-btn" data-animal="horse">🐴</button>
            <button class="animal-btn" data-animal="frog">🐸</button>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
        <h2 id="result"></h2>

        <h3>Score: <span id="score">0</span></h3>
        <h3>Stars: <span id="stars">⭐ 0</span></h3>

        <button onclick="repeatSound()">🔄 Hear Again</button>
        <br><br>
        <button onclick="location.href='../game_menu.php'">Back</button>
    </div>

</div>

<!-- ================= START MODAL ================= -->
<div id="startModal" class="start-modal">
    <div class="start-box">
        <h2>🐾 Animal Sound Game</h2>
        <button onclick="startGame()" class="start-btn">
            ▶ Start Game
        </button>
    </div>
</div>

<!-- ================= CHILD ID ================= -->
<script>
const CHILD_ID = <?php echo $_SESSION['child_id']; ?>;
</script>
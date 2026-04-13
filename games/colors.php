<?php
session_start();

if (!isset($_SESSION['child_id'])) {
    header("Location: ../dashboard.php");
    exit();
}
?>

<link rel="stylesheet" href="../css/style.css">
<script src="../js/colors.js" defer></script>

<div class="container">
    <h1>🎨 Color Game</h1>

    <div id="startScreen" style="
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:white;
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    z-index:999;
">

    <h1>🎨 Color Game</h1>

    <button onclick="startGame()" style="
        font-size:30px;
        padding:20px 40px;
        border:none;
        border-radius:20px;
        background:#ff6f61;
        color:white;
        cursor:pointer;
    ">
        ▶ Start Game
    </button>

</div>

    <h2 id="instruction">Listen 👂</h2>

    <div id="colors">
        <button class="color-btn" data-color="red" style="background:red"></button>
        <button class="color-btn" data-color="blue" style="background:blue"></button>
        <button class="color-btn" data-color="yellow" style="background:yellow"></button>
    </div>

    <h2 id="result"></h2>

    <h3>Score: <span id="score">0</span></h3>
    <h3>Stars: <span id="stars">⭐ 0</span></h3>

    <button onclick="startRound()">🔄 Hear Again</button>
    <br><br>
    <button onclick="location.href='../game_menu.php'">Back</button>
</div>

<script>
const CHILD_ID = <?php echo $_SESSION['child_id']; ?>;
</script>
<?php
session_start();
if (!isset($_SESSION['child_id'])) {
    header("Location: ../dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Sound Game – TinyLearn</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="game-container">

    <div class="left-panel">
        <h1>Animal Sound Game</h1>
        <h2 id="instruction">Listen carefully 👂</h2>
    </div>

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

    <div class="right-panel">
        <h2 id="result"></h2>
        <h3>Score: <span id="score">0</span></h3>
        <h3>Stars: <span id="stars">⭐ 0</span></h3>
        <button onclick="repeatSound()">🔄 Hear Again</button>
        <br><br>
        <button onclick="location.href='../game_menu.php'">Back</button>
    </div>

</div>

<div id="startModal" class="start-modal">
    <div class="start-box">
        <h2>🐾 Animal Sound Game</h2>
        <button onclick="startGame()">▶ Start Game</button>
    </div>
</div>

<script>
const CHILD_ID = <?php echo (int) $_SESSION['child_id']; ?>;

let gameStarted = false;
let level = 1;

const levelConfig = {
    1: ["cow", "dog", "cat"],
    2: ["cow", "dog", "cat", "duck", "sheep", "horse"],
    3: ["cow", "dog", "cat", "duck", "sheep", "horse", "lion"],
    4: ["cow", "dog", "cat", "duck", "sheep", "horse", "lion", "elephant"],
    5: ["cow", "dog", "cat", "duck", "sheep", "horse", "lion", "elephant", "frog"],
};

const roundsPerLevel = 2;
let score = 0;
let stars = 0;
let currentRound = 1;
const maxRounds = 10;
let correctAnimal;

function startGame() {
    gameStarted = true;
    level = 1;
    score = 0;
    stars = 0;
    currentRound = 1;
    document.getElementById("score").textContent = 0;
    document.getElementById("stars").textContent = "⭐ 0";
    document.getElementById("result").textContent = "";
    document.getElementById("startModal").style.display = "none";
    startRound();
}

function speak(text) {
    let speech = new SpeechSynthesisUtterance(text);
    speech.rate = 0.8;
    speech.pitch = 1;
    speechSynthesis.cancel();
    speechSynthesis.speak(speech);
}

function repeatSound() {
    if (correctAnimal) speak("Where is the " + correctAnimal + "?");
}

function shuffleButtons() {
    let container = document.getElementById("animals");
    let buttons = Array.from(container.children);
    buttons.sort(() => Math.random() - 0.5);
    buttons.forEach(btn => container.appendChild(btn));
}

function startRound() {
    if (currentRound > maxRounds) { endGame(); return; }

    level = Math.min(5, Math.ceil(currentRound / roundsPerLevel));

    let animals = levelConfig[level];
    correctAnimal = animals[Math.floor(Math.random() * animals.length)];

    document.getElementById("result").textContent = "";
    document.getElementById("instruction").textContent =
        "Level " + level + " | Round " + currentRound + " of " + maxRounds + " — Listen carefully 👂";

    speak("Where is the " + correctAnimal + "?");
    shuffleButtons();
}

document.getElementById("animals").addEventListener("click", function(e) {
    if (!gameStarted) return;
    if (!e.target.classList.contains("animal-btn")) return;
    if (currentRound > maxRounds) return;

    if (e.target.dataset.animal === correctAnimal) {
        score++;
        document.getElementById("score").textContent = score;
        document.getElementById("result").textContent = "🎉 Yay!";
        speak("Great job!");

        if (score % 3 === 0) {
            stars++;
            document.getElementById("stars").textContent = "⭐ " + stars;
        }

        currentRound++;
        saveScore();
        setTimeout(startRound, 1500);
    } else {
        document.getElementById("result").textContent = "🥺 Try again!";
        speak("Try again!");
    }
});

function endGame() {
    gameStarted = false;
    saveScore();
    speak("Game over. Well done!");
    document.getElementById("result").textContent = "🎉 Game Over! Final Score: " + score;
    document.getElementById("instruction").textContent =
        "You got " + stars + " star" + (stars !== 1 ? "s" : "") + "!";
}

function saveScore() {
    fetch("../save_score.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `child_id=${CHILD_ID}&game_type=animal&score=${score}&stars=${stars}`
    });
}
</script>

</body>
</html>
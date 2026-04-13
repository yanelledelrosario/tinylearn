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
    <title>Color Game – TinyLearn</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div id="startScreen" style="
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    background: white;
    display: flex; justify-content: center; align-items: center; flex-direction: column;
    z-index: 999;
">
    <h1>🎨 Color Game</h1>
    <button onclick="startGame()" style="
        font-size: 30px; padding: 20px 40px;
        border: none; border-radius: 20px;
        background: #ff6f61; color: white; cursor: pointer;
    ">▶ Start Game</button>
</div>

<div class="container">
    <h1>🎨 Color Game</h1>
    <h2 id="instruction">Listen 👂</h2>
    <div id="colors"></div>
    <h2 id="result"></h2>
    <h3>Score: <span id="score">0</span></h3>
    <h3>Stars: <span id="stars">⭐ 0</span></h3>
    <button onclick="repeatSound()">🔄 Hear Again</button>
    <br><br>
    <button onclick="location.href='../game_menu.php'">Back</button>
</div>

<script>
const CHILD_ID = <?php echo (int) $_SESSION['child_id']; ?>;

let level = 1;

const levelConfig = {
    1: ["red", "blue", "yellow"],
    2: ["red", "blue", "yellow", "green"],
    3: ["red", "blue", "yellow", "green", "purple", "orange"]
};

const colorCSS = {
    red: "red", blue: "blue", yellow: "yellow",
    green: "green", purple: "purple", orange: "orange"
};

let score = 0;
let stars = 0;
let currentRound = 1;
const maxRounds = 10;
let correctColor;

function startGame() {
    document.getElementById("startScreen").style.display = "none";
    score = 0; stars = 0; currentRound = 1; level = 1;
    document.getElementById("score").textContent = 0;
    document.getElementById("stars").textContent = "⭐ 0";
    renderButtons();
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
    if (correctColor) speak("Find the " + correctColor + " color");
}

function renderButtons() {
    let container = document.getElementById("colors");
    container.innerHTML = "";
    levelConfig[level].forEach(color => {
        let btn = document.createElement("button");
        btn.className = "color-btn";
        btn.dataset.color = color;
        btn.style.background = colorCSS[color];
        container.appendChild(btn);
    });
}

function startRound() {
    if (currentRound > maxRounds) { endRound(); return; }

    if (currentRound === 5 && level < 2) { level = 2; renderButtons(); }
    if (currentRound === 8 && level < 3) { level = 3; renderButtons(); }

    let colors = levelConfig[level];
    correctColor = colors[Math.floor(Math.random() * colors.length)];

    document.getElementById("result").textContent = "";
    document.getElementById("instruction").textContent =
        "Level " + level + " | Round " + currentRound + " of " + maxRounds +
        " — Find the " + correctColor + " color";

    speak("Find the " + correctColor + " color");
}

document.getElementById("colors").addEventListener("click", function(e) {
    if (!e.target.classList.contains("color-btn")) return;
    if (currentRound > maxRounds) return;

    if (e.target.dataset.color === correctColor) {
        score++;
        document.getElementById("score").textContent = score;
        document.getElementById("result").textContent = "🎉 Good job!";
        speak("Good job!");

        if (score % 3 === 0) {
            stars++;
            document.getElementById("stars").textContent = "⭐ " + stars;
            speak("You earned a star!");
        }

        currentRound++;
        saveScore();
        setTimeout(startRound, 1500);
    } else {
        document.getElementById("result").textContent = "🥺 Try again!";
        speak("Try again!");
    }
});

function endRound() {
    saveScore();
    speak("Level finished. Good job!");
    let playAgain = confirm("🎉 Level complete! Do you want another round?");
    if (playAgain) {
        score = 0; stars = 0; currentRound = 1; level = 1;
        document.getElementById("score").textContent = 0;
        document.getElementById("stars").textContent = "⭐ 0";
        renderButtons();
        startRound();
    } else {
        document.getElementById("result").textContent = "🎉 Game Over! Final Score: " + score;
    }
}

function saveScore() {
    fetch("../save_score.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `child_id=${CHILD_ID}&game_type=color&score=${score}&stars=${stars}`
    });
}
</script>

</body>
</html>
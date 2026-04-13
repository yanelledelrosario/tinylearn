let gameStarted = false;
let level = 1;

let levelConfig = {
    1: ["cow", "dog", "cat"],
    2: ["cow", "dog", "cat", "duck", "sheep", "horse"],
    3: ["cow", "dog", "cat", "duck", "sheep", "horse", "lion"],
    4: ["cow", "dog", "cat", "duck", "sheep", "horse", "lion", "elephant"],
    5: ["cow", "dog", "cat", "duck", "sheep", "horse", "lion", "elephant", "frog"],
};

let score = 0;
let stars = 0;
let currentRound = 1;
const maxRounds = 10;
let correctAnimal;

/* =========================
   START GAME
========================= */
function startGame() {

    gameStarted = true; // ✅ IMPORTANT FIX

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

/* =========================
   SPEECH
========================= */
function speak(text) {
    let speech = new SpeechSynthesisUtterance(text);
    speech.rate = 0.8;
    speech.pitch = 1;
    speechSynthesis.cancel(); // prevents stacking voices
    speechSynthesis.speak(speech);
}

/* =========================
   GAME LOGIC
========================= */
function getCurrentAnimals() {
    return levelConfig[level] || levelConfig[1];
}

function shuffleButtons() {
    let container = document.getElementById("animals");
    let buttons = Array.from(container.children);
    buttons.sort(() => Math.random() - 0.5);
    buttons.forEach(btn => container.appendChild(btn));
}

function startRound() {

    if (currentRound > maxRounds) {
        endGame();
        return;
    }

    let animals = getCurrentAnimals();

    correctAnimal = animals[Math.floor(Math.random() * animals.length)];

    document.getElementById("result").textContent = "";

    document.getElementById("instruction").textContent =
        "Level " + level +
        " | Round " + currentRound + " of " + maxRounds +
        " — Listen carefully 👂";

    speak("Where is the " + correctAnimal + "?");

    shuffleButtons();
}

/* =========================
   CLICK HANDLER
========================= */
document.getElementById("animals").addEventListener("click", function(e) {

    if (!gameStarted) return;
    if (!e.target.classList.contains("animal-btn")) return;
    if (currentRound > maxRounds) return;

    let selected = e.target.dataset.animal;

    if (selected === correctAnimal) {

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

/* =========================
   END GAME
========================= */
function endGame() {

    gameStarted = false; // stop clicks

    speak("Level finished. Good job!");

    document.getElementById("result").textContent =
        "🎉 Game Over! Final Score: " + score;
}

/* =========================
   SAVE SCORE
========================= */
function saveScore() {

    fetch("../save_score.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body:
            `child_id=${CHILD_ID}` +
            `&game_type=animal` +
            `&score=${score}` +
            `&stars=${stars}`
    });
}
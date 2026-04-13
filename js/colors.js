function startGame() {
    document.getElementById("startScreen").style.display = "none";
    startRound();
}
let level = 1;

let levelConfig = {
    1: ["red", "blue", "yellow"],
    2: ["red", "blue", "yellow", "green"],
    3: ["red", "blue", "yellow", "green", "purple", "orange"]
};

let score = 0;
let stars = 0;

let currentRound = 1;
const maxRounds = 10;

let correctColor;

function speak(text) {
    let speech = new SpeechSynthesisUtterance(text);
    speech.rate = 0.8;
    speech.pitch = 1;
    speechSynthesis.speak(speech);
}

function getCurrentColors() {
    return levelConfig[level];
}

function shuffleButtons() {
    let container = document.getElementById("colors");
    let buttons = Array.from(container.children);

    buttons.sort(() => Math.random() - 0.5);

    buttons.forEach(btn => container.appendChild(btn));
}

function startRound() {

    let colors = getCurrentColors();

    if (currentRound > maxRounds) {
        endRound();
        return;
    }

    correctColor = colors[Math.floor(Math.random() * colors.length)];

    document.getElementById("result").textContent = "";

    document.getElementById("instruction").textContent =
        "Level " + level +
        " | Round " + currentRound + " of " + maxRounds +
        " — Find the " + correctColor + " color";

    shuffleButtons();

    speak("Find the " + correctColor + " color");
}

function endRound() {

    saveScore();

    speak("Level finished. Good job!");

    let playAgain = confirm("🎉 Level complete! Do you want another round?");

    if (playAgain) {

        currentRound = 1;

        if (level < 3) level++;

        startRound();

    } else {

        document.getElementById("result").textContent =
            "🎉 Game Over! Final Score: " + score;
    }
}

document.querySelectorAll(".color-btn").forEach(button => {

    button.addEventListener("click", function() {

        if (currentRound > maxRounds) return;

        let selected = this.dataset.color;

        if (selected === correctColor) {

            score++;

            document.getElementById("score").textContent = score;

            document.getElementById("result").textContent = "🎉 Good job!";
            speak("Good job!");

            if (score % 3 === 0) {
                stars++;
                document.getElementById("stars").textContent = "⭐ " + stars;
                speak("You earned a star!");
            }

        } else {
            document.getElementById("result").textContent = "🥺 Try again!";
            speak("Try again!");
        }

        currentRound++;

        saveScore();

        setTimeout(startRound, 1500);
    });
});

function saveScore() {

    fetch("../save_score.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body:
            `child_id=${CHILD_ID}` +
            `&game_type=color` +
            `&score=${score}` +
            `&stars=${stars}`
    });
}


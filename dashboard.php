<?php
session_start();
include "includes/db.php";

if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}

$parent_id = (int) $_SESSION['parent_id'];
$stmt = $conn->prepare("SELECT * FROM children WHERE parent_id = ?");
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$children = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – TinyLearn</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['parent_name']); ?>!</h1>
    <h3>Your Children</h3>

    <?php if ($children->num_rows > 0): ?>
        <?php while ($child = $children->fetch_assoc()): ?>
            <div style="background:#ffe0d6; padding:10px; border-radius:10px; margin:10px 0;">
                <strong><?php echo htmlspecialchars($child['name']); ?></strong>
                (Age: <?php echo (int) $child['age']; ?>)
                <br><br>
                <button onclick="location.href='select_child.php?id=<?php echo (int) $child['id']; ?>'">
                    Play
                </button>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No children added yet.</p>
    <?php endif; ?>

    <br>
    <button onclick="location.href='add_child.php'">Add Child</button>
    <br><br>
    <button onclick="location.href='logout.php'">Logout</button>
</div>

</body>
</html>
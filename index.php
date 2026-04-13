<?php
session_start();

if (isset($_SESSION['parent_id'])) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}
exit();
?>
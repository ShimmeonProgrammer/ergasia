<?php
// Έναρξη session
session_start();
// Έλεγχος αν είναι καθηγητής
if(!isset($_SESSION['username']) || $_SESSION['role'] != "professor"){
    header("Location: forbidden.php");
    exit();
}
?>
<!-- CSS -->
<link rel="stylesheet" href="css/styles.css">
<div class="dashboard-container">
    <h2>Ανάθεση Εργασιών</h2>
    <!-- Εργασία -->
    <p>Εργασία Web Development</p>
    <!-- Πίσω -->
    <a href="dashboard.php">Πίσω</a>
</div>

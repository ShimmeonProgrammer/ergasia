<?php

// Έναρξη session
session_start();

// Έλεγχος αν είναι φοιτητής
if(!isset($_SESSION['username']) || $_SESSION['role'] != "student"){
    header("Location: forbidden.php");
    exit();
}
?>
<!-- CSS -->
<link rel="stylesheet" href="css/styles.css">
<div class="dashboard-container">
    <h2>Τα Μαθήματά μου</h2>
    <!-- Μαθήματα -->
    <ul>
        <li>Web Development</li>
        <li>Databases</li>
    </ul>
    <!-- Πίσω -->
    <a href="dashboard.php">Πίσω</a>
</div>

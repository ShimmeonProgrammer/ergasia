<?php

// Έναρξη session
session_start();

// Έλεγχος αν είναι φοιτητής
if(!isset($_SESSION['username']) || $_SESSION['role'] != "student"){
    header("Location: forbidden.php");
    exit();
}

// Αρχείο βαθμολογιών
$gradesFile = "uploads/grades.json";
if(!file_exists($gradesFile)){
    file_put_contents($gradesFile, json_encode(array()));
}

// Φόρτωση βαθμολογιών
$grades = json_decode(file_get_contents($gradesFile), true);
if(!is_array($grades)) $grades = array();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Βαθμολογίες</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="dashboard-container">
    <h2>📊 Οι Βαθμολογίες μου</h2>

    <?php
    // Εμφάνιση βαθμολογιών
    $found = false;
    foreach($grades as $file => $grade){
        if(strpos($file, $_SESSION['username']) !== false){
            echo "<p>" . htmlspecialchars($file) . " : <strong>" . htmlspecialchars($grade) . "/10</strong></p>";
            $found = true;
        }
    }
    if(!$found){
        echo "<p>Δεν υπάρχει βαθμολογία ακόμα.</p>";
    }
    ?>

    <!-- Πίσω -->
    <a href="dashboard.php">Πίσω</a>
</div>

</body>
</html>

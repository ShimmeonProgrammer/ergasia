<?php

// Έναρξη session
session_start();

// Έλεγχος αν είναι καθηγητής
if(!isset($_SESSION['username']) || $_SESSION['role'] != "professor"){
    header("Location: forbidden.php");
    exit();
}

// Μαθήματα καθηγητή
$courses = array('Web Development', 'Databases', 'Mobile Apps', 'Cloud Computing');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Διαχείριση Μαθημάτων</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="dashboard-container">
    <h2>📚 Διαχείριση Μαθημάτων</h2>

    <!-- Μαθήματα -->
    <ul class="dashboard-list">
    <?php foreach($courses as $course): ?>
        <li><?php echo htmlspecialchars($course); ?></li>
    <?php endforeach; ?>
    </ul>

    <!-- Πίσω -->
    <a href="dashboard.php">Πίσω</a>
</div>

</body>
</html>

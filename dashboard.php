<?php
// Έναρξη session
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<!-- Κεντρικό πλαίσιο dashboard -->
<div class="dashboard-container">

    <!-- Επικεφαλίδα -->
    <div class="dashboard-header">
        <h1>🎓 Dashboard</h1>
        <p>Καλώς ήρθες, <strong><?php echo $_SESSION['username']; ?></strong></p>
    </div>

    <!-- Μενού -->
    <div class="dashboard-nav">
        <a href="startpage.php">Αρχική</a>
        <a href="logout.php">Αποσύνδεση</a>
    </div>

    <!-- Επιλογές dashboard -->
    <div class="dashboard-grid">

<?php 
// Αν ο χρήστης είναι φοιτητής
if($_SESSION['role'] == "student"): ?>
        <!-- Μαθήματα -->
        <div class="dashboard-card">
            <h3>📘 Μαθήματα</h3>
            <p>Δες τα μαθήματά σου</p>
            <a class="dashboard-btn" href="student_courses.php">Άνοιγμα</a>
        </div>

        <!-- Υποβολή εργασιών -->
        <div class="dashboard-card">
            <h3>📝 Υποβολή Εργασιών</h3>
            <p>Ανέβασε τις εργασίες σου</p>
            <a class="dashboard-btn" href="student_assignments.php">Υποβολή</a>
        </div>

        <!-- Βαθμολογίες -->
        <div class="dashboard-card">
            <h3>📊 Βαθμολογίες</h3>
            <p>Δες τους βαθμούς σου</p>
            <a class="dashboard-btn" href="student_grades.php">Προβολή</a>
        </div>

<?php else: ?>
        <!-- Υποβολές φοιτητών -->
        <div class="dashboard-card">
            <h3>📂 Υποβολές Φοιτητών</h3>
            <p>Δες τις εργασίες που έχουν ανέβει</p>
            <a class="dashboard-btn" href="professor_grading.php">Άνοιγμα</a>
        </div>
<?php endif; ?>

</div>
    </div>

</body>
</html>


<?php
// Ξεκινάμε το session
session_start();

// Ελέγχουμε αν ο χρήστης είναι συνδεδεμένος
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

    <!-- Ενότητα επικεφαλίδας dashboard -->
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Καλώς ήρθες, <strong><?php echo $_SESSION['username']; ?></strong></p>
    </div>

    <!-- Μενού -->
    <div class="dashboard-nav">
        <a href="startpage.php">Αρχική</a>
        <a href="logout.php">Αποσύνδεση</a>
    </div>

    <!-- Ενότητα επιλογών dashboard -->
    <div class="dashboard-grid">

<?php 
// Αν ο χρήστης είναι φοιτητής
if($_SESSION['role'] == "student"): ?>
        <!-- Μαθήματα -->
    <div class="dashboard-card">
            <h3>Μαθήματα</h3>
            <p>Δες τα μαθήματά σου</p>
            <a class="dashboard-btn" href="student_courses.php">Άνοιγμα</a>
        </div>

        <!-- Υποβολή εργασιών -->
        <div class="dashboard-card">
            <h3>Υποβολή Εργασιών</h3>
            <p>Ανέβασε τις εργασίες σου</p>
            <a class="dashboard-btn" href="student_assignments.php">Υποβολή</a>
        </div>

        <!-- Βαθμολογίες -->
        <div class="dashboard-card">
            <h3>Κάρτα βαθμολογιών φοιτητή</h3>
            <p>Δες τους βαθμούς που σου έβαλε ο καθηγητής</p>
            <a class="dashboard-btn" href="student_grades.php">Προβολή</a>
        </div>

<?php else: ?>
        <!-- Διαχείριση μαθημάτων -->
        <div class="dashboard-card">
            <h3>Κάρτα διαχείρισης μαθημάτων & εργασιών καθηγητή</h3>
            <p>Διαχειρίσου τα μαθήματα και τις εργασίες σου</p>
            <a class="dashboard-btn" href="professor_courses.php">Διαχείριση</a>
        </div>
        <!-- Υποβολές φοιτητών -->
        <div class="dashboard-card">
            <h3>Κάρτα υποβολών φοιτητών καθηγητή</h3>
            <p>Δες τις εργασίες που έχουν ανέβει</p>
            <a class="dashboard-btn" href="professor_grading.php">Άνοιγμα</a>
        </div>
<?php endif; ?>

</div>
    </div>

</body>
</html>


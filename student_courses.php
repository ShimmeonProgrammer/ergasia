<?php
// Ξεκινάμε το session
session_start();

// Ελέγχουμε αν ο χρήστης είναι φοιτητής
if(!isset($_SESSION['username']) || $_SESSION['role'] != "student"){
    header("Location: forbidden.php");
    exit();
}

// Σύνδεση με τη βάση δεδομένων
$conn = mysqli_connect("localhost", "root", "", "websitedatabase");
if(!$conn){ die("Σφάλμα σύνδεσης!"); }

// Παίρνουμε τα μαθήματα που είναι εγγεγραμμένος ο φοιτητής
$student_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT c.* FROM student_courses sc JOIN courses c ON sc.course_id=c.id WHERE sc.student_id='$student_id'");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Τα Μαθήματά μου</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="dashboard-container">
    <!-- Ενότητα μαθημάτων φοιτητή -->
    <h2>Τα Μαθήματά μου</h2>
    <!-- Λίστα μαθημάτων -->
    <ul class="dashboard-list">
        <?php if(mysqli_num_rows($res) == 0): ?>
            <li>Δεν είσαι εγγεγραμμένος σε κανένα μάθημα.</li>
        <?php else: while($row = mysqli_fetch_assoc($res)): ?>
            <li>
                <strong><?php echo htmlspecialchars($row['code']); ?>:</strong> <?php echo htmlspecialchars($row['title']); ?> - <?php echo htmlspecialchars($row['description']); ?>
            </li>
        <?php endwhile; endif; ?>
    </ul>
    <!-- Κουμπί επιστροφής στο dashboard -->
    <a href="dashboard.php">Πίσω</a>
</div>
</body>
</html>

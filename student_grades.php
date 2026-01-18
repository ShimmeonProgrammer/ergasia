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

// Παίρνουμε τις βαθμολογίες του φοιτητή
$student_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT s.*, a.title as assignment_title, c.title as course_title FROM submissions s JOIN assignments a ON s.assignment_id=a.id JOIN courses c ON a.course_id=c.id WHERE s.student_id='$student_id' ORDER BY s.submitted_at DESC");
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
    <!-- Ενότητα βαθμολογιών φοιτητή -->
    <h2>Οι Βαθμολογίες μου</h2>

    <!-- Πίνακας με τις βαθμολογίες -->
    <table>
        <tr>
            <th>Μάθημα</th>
            <th>Εργασία</th>
            <th>Ημ/νία Υποβολής</th>
            <th>Βαθμός</th>
        </tr>
        <?php if(mysqli_num_rows($res) == 0): ?>
            <tr>
                <td colspan="4">Δεν υπάρχουν βαθμολογημένες εργασίες.</td>
            </tr>
        <?php else: while($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                <td><?php echo htmlspecialchars($row['assignment_title']); ?></td>
                <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                <td><?php echo is_null($row['grade']) ? '-' : htmlspecialchars($row['grade']); ?></td>
            </tr>
        <?php endwhile; endif; ?>
    </table>

    <!-- Κουμπί επιστροφής στο dashboard -->
    <a href="dashboard.php">Πίσω</a>
</div>

</body>
</html>

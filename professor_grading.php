<?php
// Ξεκινάμε το session
session_start();

// Ελέγχουμε αν ο χρήστης είναι καθηγητής
if(!isset($_SESSION['username']) || $_SESSION['role'] != "professor"){
    header("Location: forbidden.php");
    exit();
}

// Σύνδεση με τη βάση δεδομένων
$conn = mysqli_connect("localhost", "root", "", "websitedatabase");
if(!$conn){ die("Σφάλμα σύνδεσης!"); }

// Παίρνουμε τα μαθήματα του καθηγητή
$professor_id = $_SESSION['user_id'];
$courses = mysqli_query($conn, "SELECT * FROM courses WHERE professor_id='$professor_id'");

// Παίρνουμε τις εργασίες του καθηγητή
$assignments = mysqli_query($conn, "SELECT a.*, c.title as course_title FROM assignments a JOIN courses c ON a.course_id=c.id WHERE c.professor_id='$professor_id' ORDER BY a.due_date DESC");

// Παίρνουμε όλες τις υποβολές φοιτητών για κάθε εργασία
$submissions = array();
$res = mysqli_query($conn, "SELECT s.*, u.username, a.title as assignment_title FROM submissions s JOIN users u ON s.student_id=u.id JOIN assignments a ON s.assignment_id=a.id WHERE a.id IN (SELECT id FROM assignments WHERE course_id IN (SELECT id FROM courses WHERE professor_id='$professor_id'))");
while($row = mysqli_fetch_assoc($res)){
    $submissions[] = $row;
}

// Βαθμολόγηση υποβολής από τον καθηγητή
if(isset($_POST['grade_submit'])){
    $sub_id = (int)$_POST['sub_id'];
    $grade = (int)$_POST['grade'];
    mysqli_query($conn, "UPDATE submissions SET grade='$grade' WHERE id='$sub_id'");
    header("Location: professor_grading.php");
    exit();
}
?>
<link rel="stylesheet" href="css/styles.css">
<div class="dashboard-container">
    <h2>Υποβολές Φοιτητών & Βαθμολόγηση</h2>
    <table>
        <tr><th>Εργασία</th><th>Φοιτητής</th><th>Αρχείο</th><th>Ημ/νία</th><th>Βαθμός</th><th>Ενέργεια</th></tr>
        <?php foreach($submissions as $s): ?>
        <tr>
            <td><?php echo htmlspecialchars($s['assignment_title']); ?></td>
            <td><?php echo htmlspecialchars($s['username']); ?></td>
            <td><a href="<?php echo htmlspecialchars($s['file_path']); ?>" target="_blank">Λήψη</a></td>
            <td><?php echo htmlspecialchars($s['submitted_at']); ?></td>
            <td><?php echo is_null($s['grade']) ? '-' : htmlspecialchars($s['grade']); ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="sub_id" value="<?php echo $s['id']; ?>">
                    <input type="number" name="grade" min="0" max="10" value="<?php echo is_null($s['grade']) ? '' : htmlspecialchars($s['grade']); ?>" required>
                    <button type="submit" name="grade_submit">Βαθμολόγηση</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="dashboard.php">Πίσω</a>
</div>

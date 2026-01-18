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

// Μήνυμα για τον χρήστη
$message = "";

// Παίρνουμε τις εργασίες για τα μαθήματα του φοιτητή
$student_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT a.*, c.title as course_title FROM assignments a JOIN student_courses sc ON a.course_id=sc.course_id JOIN courses c ON c.id=a.course_id WHERE sc.student_id='$student_id' ORDER BY a.due_date DESC");
$assignments = array();
while($row = mysqli_fetch_assoc($res)){
    $assignments[] = $row;
}

// Υποβολή εργασίας από τον φοιτητή
if(isset($_POST['submit']) && isset($_FILES["assignment"])){
    $assignment_id = (int)$_POST['assignment_id'];
    $targetDir = "uploads/";
    $fileName = basename($_FILES["assignment"]["name"]);
    $fileSize = $_FILES["assignment"]["size"];
    $fileTmp = $_FILES["assignment"]["tmp_name"];
    $maxSize = 10 * 1024 * 1024;
    $allowedExtensions = array('pdf', 'docx', 'doc', 'txt', 'zip');
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Έλεγχος μεγέθους αρχείου
    if($fileSize > $maxSize){
        $message = "Το αρχείο είναι πολύ μεγάλο! (Max: 10MB)";
    } elseif(!in_array($fileExt, $allowedExtensions)){
        $message = "Μη επιτρεπτός τύπος αρχείου! (pdf, docx, doc, txt, zip)";
    } else {
        $targetFile = $targetDir . time() . "_" . $_SESSION['username'] . "_" . $fileName;
        // Έλεγχος αν υπάρχει ήδη υποβολή για αυτή την εργασία
        $check = mysqli_query($conn, "SELECT * FROM submissions WHERE assignment_id='$assignment_id' AND student_id='$student_id'");
        if(mysqli_num_rows($check) > 0){
            $message = "Έχεις ήδη υποβάλει για αυτή την εργασία!";
        } else if(move_uploaded_file($fileTmp, $targetFile)){
            mysqli_query($conn, "INSERT INTO submissions (assignment_id, student_id, file_path) VALUES ('$assignment_id', '$student_id', '$targetFile')");
            $message = "Η εργασία υποβλήθηκε επιτυχώς!";
        } else {
            $message = "Σφάλμα κατά την υποβολή!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Υποβολή Εργασίας</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="dashboard-container">
    <!-- Ενότητα υποβολής εργασίας φοιτητή -->
    <h2>Υποβολή Εργασίας</h2>

    <!-- Εμφάνιση μηνύματος χρήστη -->
    <?php if($message != "") echo "<p>$message</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <select name="assignment_id" required>
        <option value="">Επιλογή εργασίας</option>
        <?php foreach($assignments as $a): ?>
            <option value="<?php echo $a['id']; ?>"><?php echo htmlspecialchars($a['course_title']." - ".$a['title']); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="file" name="assignment" required>
    <input type="submit" name="submit" value="Υποβολή">
</form>

<br>
<a href="dashboard.php">Πίσω στο Dashboard</a>
</div>

</body>
</html>

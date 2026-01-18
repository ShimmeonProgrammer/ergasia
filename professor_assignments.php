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

// Αν ο καθηγητής προσθέσει νέα εργασία
if(isset($_POST['add_assignment'])){
    $course_id = (int)$_POST['course_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    mysqli_query($conn, "INSERT INTO assignments (course_id, title, description, due_date) VALUES ('$course_id', '$title', '$description', '$due_date')");
}

// Αν ο καθηγητής διαγράψει εργασία
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM assignments WHERE id='$id'");
    header("Location: professor_assignments.php");
    exit();
}

// Παίρνουμε τα μαθήματα του καθηγητή
$professor_id = $_SESSION['user_id'];
$courses = mysqli_query($conn, "SELECT * FROM courses WHERE professor_id='$professor_id'");

// Παίρνουμε τις εργασίες του καθηγητή
$assignments = mysqli_query($conn, "SELECT a.*, c.title as course_title FROM assignments a JOIN courses c ON a.course_id=c.id WHERE c.professor_id='$professor_id' ORDER BY a.due_date DESC");
?>
<!-- Εισαγωγή CSS για εμφάνιση -->
<link rel="stylesheet" href="css/styles.css">
<div class="dashboard-container">
    <!-- Τίτλος σελίδας -->
    <h2>Ανάθεση Εργασιών</h2>
    <!-- Φόρμα προσθήκης νέας εργασίας -->
    <form method="post" style="margin-bottom:20px;">
        <select name="course_id" required>
            <option value="">Επιλογή μαθήματος</option>
            <?php while($c = mysqli_fetch_assoc($courses)): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['title']); ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="title" placeholder="Τίτλος εργασίας" required>
        <input type="text" name="description" placeholder="Περιγραφή" required>
        <input type="date" name="due_date" required>
        <button type="submit" name="add_assignment">Προσθήκη</button>
    </form>
    <!-- Πίνακας με τις εργασίες του καθηγητή -->
    <table>
        <tr><th>Μάθημα</th><th>Τίτλος</th><th>Περιγραφή</th><th>Προθεσμία</th><th>Ενέργεια</th></tr>
        <?php while($a = mysqli_fetch_assoc($assignments)): ?>
        <tr>
            <td><?php echo htmlspecialchars($a['course_title']); ?></td>
            <td><?php echo htmlspecialchars($a['title']); ?></td>
            <td><?php echo htmlspecialchars($a['description']); ?></td>
            <td><?php echo htmlspecialchars($a['due_date']); ?></td>
            <td><a href="?delete=<?php echo $a['id']; ?>" style="color:red;" onclick="return confirm('Διαγραφή;');">Διαγραφή</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <!-- Κουμπί επιστροφής στο dashboard -->
    <a href="dashboard.php">Πίσω</a>
</div>

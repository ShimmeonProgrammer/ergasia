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
if(!$conn){
    die("Σφάλμα σύνδεσης με τη βάση δεδομένων!");
}

// Παίρνουμε το id του καθηγητή
$professor_id = $_SESSION['user_id'];

// Παίρνουμε όλους τους φοιτητές
$students = mysqli_query($conn, "SELECT id, username FROM users WHERE role='student'");

// Αν ο καθηγητής προσθέσει νέο μάθημα
if(isset($_POST['add_course'])){
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    mysqli_query($conn, "INSERT INTO courses (code, title, professor_id, description) VALUES ('$code', '$title', '$professor_id', '$description')");
    $course_id = mysqli_insert_id($conn);
    if(isset($_POST['students']) && is_array($_POST['students'])){
        foreach($_POST['students'] as $student_id){
            $student_id = (int)$student_id;
            mysqli_query($conn, "INSERT INTO student_courses (student_id, course_id) VALUES ('$student_id', '$course_id')");
        }
    }
}

// Αν ο καθηγητής διαγράψει μάθημα
if(isset($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM courses WHERE id='$delete_id' AND professor_id='$professor_id'");
    header("Location: professor_courses.php");
    exit();
}

// Αν ο καθηγητής επεξεργαστεί μάθημα
if(isset($_POST['edit_course'])){
    $edit_id = (int)$_POST['edit_id'];
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    mysqli_query($conn, "UPDATE courses SET code='$code', title='$title', description='$description' WHERE id='$edit_id' AND professor_id='$professor_id'");
}

// Παίρνουμε τα μαθήματα του καθηγητή
$courses = mysqli_query($conn, "SELECT * FROM courses WHERE professor_id='$professor_id'");

// Αν ο καθηγητής προσθέσει νέα εργασία
if(isset($_POST['add_assignment'])){
    $course_id = (int)$_POST['course_id'];
    $title = mysqli_real_escape_string($conn, $_POST['assignment_title']);
    $description = mysqli_real_escape_string($conn, $_POST['assignment_description']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    mysqli_query($conn, "INSERT INTO assignments (course_id, title, description, due_date) VALUES ('$course_id', '$title', '$description', '$due_date')");
}

// Αν ο καθηγητής διαγράψει εργασία
if(isset($_GET['delete_assignment'])){
    $id = (int)$_GET['delete_assignment'];
    mysqli_query($conn, "DELETE FROM assignments WHERE id='$id'");
    header("Location: professor_courses.php");
    exit();
}

// Παίρνουμε τις εργασίες του καθηγητή
$assignments = mysqli_query($conn, "SELECT a.*, c.title as course_title FROM assignments a JOIN courses c ON a.course_id=c.id WHERE c.professor_id='$professor_id' ORDER BY a.due_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Διαχείριση Μαθημάτων & Εργασιών</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="dashboard-container">
    <!-- Ενότητα διαχείρισης μαθημάτων: Ο καθηγητής μπορεί να προσθέσει νέο μάθημα και να επιλέξει φοιτητές -->
    <h2>Διαχείριση Μαθημάτων</h2>
    <!-- Φόρμα προσθήκης νέου μαθήματος -->
    <form method="post" style="margin-bottom:30px;">
        <input type="text" name="code" placeholder="Κωδικός μαθήματος" required>
        <input type="text" name="title" placeholder="Τίτλος μαθήματος" required>
        <input type="text" name="description" placeholder="Περιγραφή" required>
        <select name="students[]" multiple size="3" style="min-width:120px;">
            <?php while($s = mysqli_fetch_assoc($students)): ?>
                <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['username']); ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="add_course">Προσθήκη</button>
    </form>
    <!-- Λίστα μαθημάτων που έχει δημιουργήσει ο καθηγητής -->
    <ul class="dashboard-list">
    <?php while($course = mysqli_fetch_assoc($courses)): ?>
        <li>
            <form method="post" style="display:inline;">
                <input type="hidden" name="edit_id" value="<?php echo $course['id']; ?>">
                <input type="text" name="code" value="<?php echo htmlspecialchars($course['code']); ?>" required style="width:80px;">
                <input type="text" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required style="width:150px;">
                <input type="text" name="description" value="<?php echo htmlspecialchars($course['description']); ?>" required style="width:200px;">
                <button type="submit" name="edit_course">Αποθήκευση</button>
            </form>
            <a href="?delete=<?php echo $course['id']; ?>" onclick="return confirm('Διαγραφή μαθήματος;');" style="color:red;margin-left:10px;">Διαγραφή</a>
        </li>
    <?php endwhile; ?>
    </ul>
    <!-- Ενότητα διαχείρισης εργασιών: Ο καθηγητής μπορεί να προσθέσει εργασία σε μάθημα -->
    <h2>Διαχείριση Εργασιών</h2>
    <!-- Φόρμα προσθήκης νέας εργασίας -->
    <form method="post" style="margin-bottom:30px;">
        <select name="course_id" required>
            <option value="">Επιλογή μαθήματος</option>
            <?php
            $courses2 = mysqli_query($conn, "SELECT * FROM courses WHERE professor_id='$professor_id'");
            while($c = mysqli_fetch_assoc($courses2)):
            ?>
                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['title']); ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="assignment_title" placeholder="Τίτλος εργασίας" required>
        <input type="text" name="assignment_description" placeholder="Περιγραφή" required>
        <input type="date" name="due_date" required>
        <button type="submit" name="add_assignment">Προσθήκη</button>
    </form>
    <!-- Πίνακας με όλες τις εργασίες που έχει δημιουργήσει ο καθηγητής -->
    <table>
        <tr><th>Μάθημα</th><th>Τίτλος</th><th>Περιγραφή</th><th>Προθεσμία</th><th>Ενέργεια</th></tr>
        <?php while($a = mysqli_fetch_assoc($assignments)): ?>
        <tr>
            <td><?php echo htmlspecialchars($a['course_title']); ?></td>
            <td><?php echo htmlspecialchars($a['title']); ?></td>
            <td><?php echo htmlspecialchars($a['description']); ?></td>
            <td><?php echo htmlspecialchars($a['due_date']); ?></td>
            <td><a href="?delete_assignment=<?php echo $a['id']; ?>" style="color:red;" onclick="return confirm('Διαγραφή;');">Διαγραφή</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="dashboard.php">Πίσω</a>
</div>
</body>
</html>

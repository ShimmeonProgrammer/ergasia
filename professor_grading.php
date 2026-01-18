<?php

// Έναρξη session
session_start();

// Έλεγχος αν είναι καθηγητής
if(!isset($_SESSION['username']) || $_SESSION['role'] != "professor"){
    header("Location: forbidden.php");
    exit();
}

// Υποβολές φοιτητών
$uploadDir = "uploads/";
$files = array_diff(scandir($uploadDir), array('.', '..', 'grades.json'));

// Αρχείο βαθμολογιών
$gradesFile = "uploads/grades.json";
if(!file_exists($gradesFile)){
    file_put_contents($gradesFile, json_encode(array()));
}

// Φόρτωση βαθμολογιών
$grades = json_decode(file_get_contents($gradesFile), true);
if(!is_array($grades)) $grades = array();

// Ενημέρωση βαθμολογίας
if(isset($_POST['grade']) && isset($_POST['file'])){
    $file = $_POST['file'];
    $grade = (int)$_POST['grade'];
    if($grade >= 0 && $grade <= 10){
        $grades[$file] = $grade;
        file_put_contents($gradesFile, json_encode($grades));
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Βαθμολόγηση</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="dashboard-container">
<h1>📂 Υποβολές Φοιτητών</h1>

<table>
<tr>
    <th>Αρχείο</th>
    <th>Λήψη</th>
    <th>Βαθμός</th>
</tr>

<?php foreach($files as $file): ?>
<tr>
    <td><?php echo htmlspecialchars($file); ?></td>
    <td><a href="uploads/<?php echo urlencode($file); ?>" target="_blank">Άνοιγμα</a></td>
    <td>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="file" value="<?php echo htmlspecialchars($file); ?>">
            <input type="number" name="grade" min="0" max="10"
                   value="<?php echo isset($grades[$file]) ? htmlspecialchars($grades[$file]) : ''; ?>" required>
            <input type="submit" value="Αποθήκευση">
        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>

<br>
<a href="dashboard.php">⬅ Πίσω στο Dashboard</a>
</div>

</body>
</html>

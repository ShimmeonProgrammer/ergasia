<?php

// Έναρξη session
session_start();

// Έλεγχος αν είναι φοιτητής
if(!isset($_SESSION['username']) || $_SESSION['role'] != "student"){
    header("Location: forbidden.php");
    exit();
}

// Μήνυμα
$message = "";

// Έλεγχος υποβολής και αρχείου
if(isset($_POST['submit']) && isset($_FILES["assignment"])){
    
    $targetDir = "uploads/"; // Φάκελος
    
    $fileName = basename($_FILES["assignment"]["name"]); // Όνομα
    
    $fileSize = $_FILES["assignment"]["size"]; // Μέγεθος
    
    $fileTmp = $_FILES["assignment"]["tmp_name"]; // Προσωρινό
    
    $maxSize = 10 * 1024 * 1024; // Μέγιστο
    
    $allowedExtensions = array('pdf', 'docx', 'doc', 'txt', 'zip'); // Τύποι
    
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // Επέκταση
    
    // Έλεγχος μεγέθους
    if($fileSize > $maxSize){
        $message = "Το αρχείο είναι πολύ μεγάλο! (Max: 10MB)";
    } elseif(!in_array($fileExt, $allowedExtensions)){
        // Έλεγχος τύπου
        $message = "Μη επιτρεπτός τύπος αρχείου! (pdf, docx, doc, txt, zip)";
    } else {
        // Τελικό όνομα
        $targetFile = $targetDir . time() . "_" . $_SESSION['username'] . "_" . $fileName;
        
        // Μεταφορά αρχείου
        if(move_uploaded_file($fileTmp, $targetFile)){
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
<h2>📝 Υποβολή Εργασίας</h2>

<?php if($message != "") echo "<p>$message</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="assignment" required>
    <br><br>
    <input type="submit" name="submit" value="Υποβολή">
</form>

<br>
<a href="dashboard.php">Πίσω στο Dashboard</a>
</div>

</body>
</html>

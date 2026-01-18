<?php

// Έναρξη session
session_start();

// Σύνδεση με βάση
$conn = mysqli_connect("localhost","root","","websitedatabase");
if(!$conn){
    die("Σφάλμα σύνδεσης με τη βάση δεδομένων!");
}

// Καθαρισμός εισόδου
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Μήνυμα
$message = "";

// Επεξεργασία φόρμας
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);
    $code = test_input($_POST['code']);

    // Έλεγχος κωδικού
    if(($role == "student" && $code != "STUD2025") || ($role == "professor" && $code != "PROF2025")) {
        $message = "Λανθασμένος ειδικός κωδικός!";
    } else {
        // Έλεγχος αν υπάρχει χρήστης
        $check = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $result = mysqli_query($conn, $check);

        if(mysqli_num_rows($result) > 0){
            $message = "Το username ή το email υπάρχει ήδη!";
        } else {
            // Εισαγωγή χρήστη
            $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
            if(mysqli_query($conn, $sql)){
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Σφάλμα κατά την εγγραφή!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Εγγραφή Χρήστη</title>
    <link rel="stylesheet" href="css/LOGSTYLE.css">
</head>
<body>

<form action="register.php" method="POST">
    <h2>Εγγραφή Χρήστη</h2>
    <?php if($message != "") { echo "<p style='color:red;'>$message</p>"; } ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Κωδικός" required>
    
    <label>Ρόλος:</label>
    <select name="role" required>
        <option value="">--Επιλέξτε--</option>
        <option value="student">Φοιτητής</option>
        <option value="professor">Καθηγητής</option>
    </select>

    <input type="text" name="code" placeholder="Ειδικός Κωδικός" required>
    <input type="submit" value="Εγγραφή">
</form>

</body>
</html>
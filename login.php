<?php

// Έναρξη session
session_start();

// Καθαρισμός εισόδου
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Αν ο χρήστης είναι ήδη συνδεδεμένος
if(isset($_SESSION['username'])){
    header("Location: startpage.php");
    exit();
}

// Σύνδεση με βάση
$conn = mysqli_connect("localhost", "root", "", "websitedatabase");
if(!$conn){
    die("Σφάλμα σύνδεσης με τη βάση δεδομένων!");
}

// Μήνυμα
$message = "";

// Επεξεργασία φόρμας
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);

    // Έλεγχος στοιχείων
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result) == 1){
        // Αποθήκευση στοιχείων στο session
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Ανακατεύθυνση
        header("Location: startpage.php");
        exit();
    } else {
        // Μήνυμα λάθους
        $message = "Λανθασμένο email ή κωδικός!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Σύνδεση Χρήστη</title>
    <link rel="stylesheet" href="css/LOGSTYLE.css">
</head>
<body>

<form action="login.php" method="POST">
    <h2>Σύνδεση Χρήστη</h2>
    <?php if(isset($message)) echo "<p style='color:red;'>$message</p>"; ?>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Κωδικός" required>
    <input type="submit" value="Σύνδεση">
</form>

</body>
</html>
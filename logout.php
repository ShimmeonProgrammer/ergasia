<?php
// Έναρξη session
session_start();

// Καθαρισμός session
session_unset();
// Καταστροφή session
session_destroy();

// Ανακατεύθυνση
header("Location: startpage.php");
exit();
?>

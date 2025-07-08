<?php
session_start();
session_unset();
session_destroy();

// Redirect to login or home page
header("Location: /Hmed/index.php"); // or /Hmed/index.php
exit;
?>

<?php
$host="localhost"; $user="root"; $pass=""; $dbname="hmed";
$conn = mysqli_connect($host,$user,$pass,$dbname);
if (!$conn) die("DB Error: ".mysqli_connect_error());
?>

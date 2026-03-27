<?php
session_start();

if (!isset($_SESSION['ulogovan']) || $_SESSION['ulogovan'] != 'da' || $_SESSION['Tip'] != 'admin') {
    header("Location: ulogujse2.php");
    exit();
}
?>
<?php
require '../config.php';
unset($_SESSION['admin']);
header('Location: login.php');
exit;
?>
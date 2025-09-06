<?php
require '../config.php';
if(!isset($_SESSION['user'])) { header('Location: ../login.php'); exit; }
if($_SERVER['REQUEST_METHOD']=='POST'){
    $doctor_id = (int)$_POST['doctor_id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    $pdo->prepare('INSERT INTO reviews (user_id,doctor_id,rating,comment) VALUES (?,?,?,?)')
        ->execute([$_SESSION['user']['user_id'],$doctor_id,$rating,$comment]);
    header('Location: dashboard.php');
    exit;
}
header('Location: dashboard.php'); exit;
?>
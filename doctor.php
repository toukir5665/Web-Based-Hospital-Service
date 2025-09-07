<?php
require 'config.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM doctors WHERE doctor_id=?');
$stmt->execute([$id]);
$d = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$d) { header('Location: index.php'); exit; }

// handle appointment
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(!isset($_SESSION['user'])){ header('Location: login.php'); exit; }
    $date = $_POST['date']; $time = $_POST['time'];
    $pdo->prepare('INSERT INTO appointments (user_id,doctor_id,date,time) VALUES (?,?,?,?)')
        ->execute([$_SESSION['user']['user_id'],$id,$date,$time]);
    $msg = 'Appointment requested. Await admin approval.';
}

// fetch approved reviews
$reviews = $pdo->prepare('SELECT r.*, u.name as user_name FROM reviews r LEFT JOIN users u ON r.user_id=u.user_id WHERE r.doctor_id=? AND status="approved" ORDER BY r.created_at DESC');
$reviews->execute([$id]);
$reviews = $reviews->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Doctor</title>
<link rel="stylesheet" href="assets/style.css"></head><body>
<div class="container">
<a href="index.php">&larr; Back</a>
<h2><?php echo htmlspecialchars($d['name']); ?></h2>
<p><strong>Specialization:</strong> <?php echo htmlspecialchars($d['specialization']); ?></p>
<p><strong>Availability:</strong> <?php echo htmlspecialchars($d['availability']); ?></p>

<?php if(isset($msg)) echo '<p style="color:green">'.htmlspecialchars($msg).'</p>'; ?>

<h3>Book Appointment</h3>
<?php if(isset($_SESSION['user'])): ?>
  <form method="post">
    <input type="date" name="date" required>
    <input type="time" name="time" required>
    <button class="btn" type="submit">Request Appointment</button>
  </form>
<?php else: ?>
  <p>Please <a href="login.php">login</a> to request appointment.</p>
<?php endif; ?>

<h3>Patient Reviews</h3>
<?php foreach($reviews as $r): ?>
  <div class="card"><strong><?php echo htmlspecialchars($r['user_name']); ?></strong> (<?php echo $r['rating']; ?>/5)<p><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p></div>
<?php endforeach; ?>
<p class="small">You can leave a review after your appointment from your user dashboard.</p>
</div></body></html>

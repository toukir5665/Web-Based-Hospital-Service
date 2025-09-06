<?php
require '../config.php';
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
if(isset($_GET['action']) && isset($_GET['id'])){
    $id=(int)$_GET['id']; $a=$_GET['action'];
    if($a=='approve') $pdo->prepare('UPDATE appointments SET status="approved" WHERE appointment_id=?')->execute([$id]);
    if($a=='cancel') $pdo->prepare('UPDATE appointments SET status="cancelled" WHERE appointment_id=?')->execute([$id]);
    header('Location: appointments.php'); exit;
}
$appointments = $pdo->query('SELECT a.*, u.name as user_name, d.name as doctor_name FROM appointments a LEFT JOIN users u ON a.user_id=u.user_id LEFT JOIN doctors d ON a.doctor_id=d.doctor_id ORDER BY a.created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Appointments</title><link rel="stylesheet" href="../assets/style.css"></head><body>
<div class="container"><h2>Appointments</h2><p><a href="dashboard.php">Back</a></p>
<?php foreach($appointments as $a): ?>
  <div class="card">
    <strong><?php echo htmlspecialchars($a['user_name']); ?></strong> with <?php echo htmlspecialchars($a['doctor_name']); ?>
    <p><?php echo $a['date']; ?> <?php echo $a['time']; ?> - <?php echo $a['status']; ?></p>
    <p>
      <a class="btn" href="appointments.php?action=approve&id=<?php echo $a['appointment_id']; ?>">Approve</a>
      <a class="btn" href="appointments.php?action=cancel&id=<?php echo $a['appointment_id']; ?>">Cancel</a>
    </p>
  </div>
<?php endforeach; ?>
</div></body></html>

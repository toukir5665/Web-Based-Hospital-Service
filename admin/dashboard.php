<?php
require '../config.php';
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
// basic counts
$c1 = $pdo->query('SELECT COUNT(*) FROM doctors')->fetchColumn();
$c2 = $pdo->query('SELECT COUNT(*) FROM appointments')->fetchColumn();
$c3 = $pdo->query('SELECT COUNT(*) FROM reviews WHERE status="pending"')->fetchColumn();
$doctors = $pdo->query('SELECT * FROM doctors ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
$appointments = $pdo->query('SELECT a.*, u.name as user_name, d.name as doctor_name FROM appointments a LEFT JOIN users u ON a.user_id=u.user_id LEFT JOIN doctors d ON a.doctor_id=d.doctor_id ORDER BY a.created_at DESC LIMIT 20')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Dashboard</title>
<link rel="stylesheet" href="../assets/style.css"></head><body>
<div class="container">
  <h2>Admin Dashboard</h2>
  <p><a href="logout.php">Logout</a> | <a href="../index.php">View Site</a></p>
  <div class="grid">
    <div class="card"><h3>Doctors</h3><p><?php echo $c1; ?></p><p><a href="doctors.php" class="btn">Manage</a></p></div>
    <div class="card"><h3>Appointments</h3><p><?php echo $c2; ?></p><p><a href="appointments.php" class="btn">View</a></p></div>
    <div class="card"><h3>Pending Reviews</h3><p><?php echo $c3; ?></p><p><a href="reviews.php" class="btn">Moderate</a></p></div>
    <div class="card"><h3>Blogs</h3><p><?php echo $c1; ?></p><p><a href="blogs.php" class="btn">Manage</a></p></div>
  </div>

  <h3>Recent Appointments</h3>
  <?php foreach($appointments as $a): ?>
    <div class="card">
      <strong><?php echo htmlspecialchars($a['user_name']); ?></strong> with <strong><?php echo htmlspecialchars($a['doctor_name']); ?></strong>
      <p><?php echo $a['date']; ?> <?php echo $a['time']; ?> - <?php echo $a['status']; ?></p>
    </div>
  <?php endforeach; ?>
</div></body></html>

<?php
require '../config.php';
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

// Handle approve/reject actions
if(isset($_GET['action']) && isset($_GET['id'])){
    $id = (int)$_GET['id'];
    if($_GET['action']=='approve') {
        $pdo->prepare('UPDATE reviews SET status="approved" WHERE review_id=?')->execute([$id]);
    }
    if($_GET['action']=='reject') {
        $pdo->prepare('UPDATE reviews SET status="rejected" WHERE review_id=?')->execute([$id]);
    }
    header('Location: reviews.php'); exit;
}

// Fetch all reviews for moderation
$reviews = $pdo->query('
    SELECT r.*, u.name AS user_name, d.name AS doctor_name
    FROM reviews r
    LEFT JOIN users u ON r.user_id=u.user_id
    LEFT JOIN doctors d ON r.doctor_id=d.doctor_id
    ORDER BY r.created_at DESC
')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Moderate Reviews</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
  <h2>Moderate Reviews</h2>
  <p><a href="dashboard.php">Back</a></p>
  <?php foreach($reviews as $r): ?>
    <div class="card">
      <strong><?php echo htmlspecialchars($r['user_name']); ?></strong> 
      about <strong><?php echo htmlspecialchars($r['doctor_name']); ?></strong>
      <p><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p>
      <p>Status: <?php echo htmlspecialchars($r['status']); ?></p>
      <p>
        <a class="btn" href="reviews.php?action=approve&id=<?php echo $r['review_id']; ?>">Approve</a>
        <a class="btn" href="reviews.php?action=reject&id=<?php echo $r['review_id']; ?>">Reject</a>
      </p>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>

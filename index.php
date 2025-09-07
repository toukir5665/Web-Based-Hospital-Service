<?php
require 'config.php';
// fetch doctors and blogs
$doctors = $pdo->query('SELECT * FROM doctors ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
$blogs = $pdo->query('SELECT * FROM blogs ORDER BY created_at DESC LIMIT 3')->fetchAll(PDO::FETCH_ASSOC);
$social = $pdo->query('SELECT * FROM social_links WHERE id=1')->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Hospital Service System</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
  <h1>Hospital Service System</h1>
  <nav>
    <a href="index.php">Home</a>
    <a href="register.php">Register</a>
    <a href="login.php">Login</a>
    <a href="admin/login.php">Admin</a>
  </nav>
</header>

<main class="container">
  <section>
    <h2>Available Doctors</h2>
    <div class="grid">
      <?php foreach($doctors as $d): ?>
        <div class="card">
          <h3><?php echo htmlspecialchars($d['name']); ?></h3>
          <p><strong>Specialization:</strong> <?php echo htmlspecialchars($d['specialization']); ?></p>
          <p><strong>Availability:</strong> <?php echo htmlspecialchars($d['availability']); ?></p>
          <a class="btn" href="doctor.php?id=<?php echo $d['doctor_id']; ?>">View & Book</a>
        </div>
      <?php endforeach; ?>
      <?php if(empty($doctors)) echo '<p>No doctors yet. Admin can add doctors from dashboard.</p>'; ?>
    </div>
  </section>

  <section>
    <h2>Latest Blogs</h2>
    <div class="grid">
     <?php foreach($blogs as $b): ?>
        <article class="blog">
          <h3><?php echo htmlspecialchars($b['title']); ?></h3>
          <p><?php echo nl2br(htmlspecialchars(substr($b['content'],0,250))); ?>...</p>
       </article>
      <?php endforeach; ?>
    </div>  
  </section>

<section>
  <h2>Patient Reviews</h2>
  <?php if(!empty($reviews)): ?>
    <?php foreach($reviews as $r): ?>
      <article class="review card">
        <h3><?php echo htmlspecialchars($r['user_name']); ?> 
          rated <?php echo (int)$r['rating']; ?>/5</h3>
        <p><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p>
        <small>
          For Dr. <?php echo htmlspecialchars($r['doctor_name']); ?> — 
          <?php echo htmlspecialchars($r['date']); ?>
        </small>
      </article>
    <?php endforeach; ?>
    <p><a href="all_reviews.php">See all reviews →</a></p>
  <?php else: ?>
    <p>No reviews yet. Be the first to leave one!</p>
  <?php endif; ?>
</section>

  <section>
    <h2>Contact & Social</h2>
    <p>Follow us:</p>
    <p>
      <a href="<?php echo htmlspecialchars($social['facebook_url']); ?>">Facebook</a> |
      <a href="<?php echo htmlspecialchars($social['twitter_url']); ?>">Twitter</a> |
      <a href="<?php echo htmlspecialchars($social['instagram_url']); ?>">Instagram</a>
    </p>
  </section>
</main>

<footer class="site-footer">
  <p>&copy; Hospital Service System</p>
</footer>
</body>
</html>

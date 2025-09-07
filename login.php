<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email=?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($pass, $user['password'])){
        $_SESSION['user'] = $user;
        header('Location: user/dashboard.php');
        exit;
    } else $error = 'Invalid credentials.';
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>User Login</title>
<link rel="stylesheet" href="assets/style.css"></head><body>
<div class="container">
<h2>Patient Login</h2>
<?php if(isset($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
  <input name="email" placeholder="Email" required>
  <input name="password" type="password" placeholder="Password" required>
  <button class="btn" type="submit">Login</button>
</form>
</div></body></html>

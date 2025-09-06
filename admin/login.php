<?php
require '../config.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $email=trim($_POST['email']);
    $pass=$_POST['password'];
    $stmt=$pdo->prepare('SELECT * FROM admins WHERE email=?');
    $stmt->execute([$email]);
    $a=$stmt->fetch(PDO::FETCH_ASSOC);
    if($a && password_verify($pass,$a['password'])){
        $_SESSION['admin']=$a;
        header('Location: dashboard.php');
        exit;
    } else $error='Invalid admin credentials.';
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Login</title>
<link rel="stylesheet" href="../assets/style.css"></head><body>
<div class="container"><h2>Admin Login</h2>
<?php if(isset($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
  <input name="email" placeholder="Email">
  <input name="password" type="password" placeholder="Password">
  <button class="btn" type="submit">Login</button>
</form>
<p class="small">Need to register? <a href="register.php">Register (admin)</a></p>
</div></body></html>

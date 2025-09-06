<?php
require '../config.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $name=trim($_POST['name']);
    $email=trim($_POST['email']);
    $pass=$_POST['password'];
    $passcode=trim($_POST['passcode']);
    if($passcode == ADMIN_REG_PASSCODE){ $error='Invalid admin passcode.'; }
    else {
        $stmt=$pdo->prepare('SELECT admin_id FROM admins WHERE email=?');
        $stmt->execute([$email]);
        if($stmt->fetch()) $error='Email already used.';
        else {
            $hash=password_hash($pass, PASSWORD_DEFAULT);
            $pdo->prepare('INSERT INTO admins (name,email,password) VALUES (?,?,?)')->execute([$name,$email,$hash]);
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Register</title>
<link rel="stylesheet" href="../assets/style.css"></head><body>
<div class="container"><h2>Admin Register</h2>
<?php if(isset($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
  <input name="name" placeholder="Full name">
  <input name="email" placeholder="Email">
  <input name="password" type="password" placeholder="Password">
  <input name="passcode" placeholder="Admin passcode">
  <button class="btn" type="submit">Register</button>
</form>
</div></body></html>

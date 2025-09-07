<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    if(!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6){
        $error = 'Please fill valid details (password min 6 chars).';
    } else {
        $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email=?');
        $stmt->execute([$email]);
        if($stmt->fetch()) $error = 'Email already registered.';
        else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)')
                ->execute([$name,$email,$hash]);
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>User Register</title>
<link rel="stylesheet" href="assets/style.css"></head><body>
<div class="container">
<h2>Register (Patient)</h2>
<?php if(isset($error)) echo '<p class="small" style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post" onsubmit="return validate();">
  <input name="name" placeholder="Full name" required>
  <input name="email" placeholder="Email" required>
  <input name="password" type="password" placeholder="Password (min 6)" required>
  <button class="btn" type="submit">Register</button>
</form>
<p class="small">Already registered? <a href="login.php">Login here</a></p>
</div>
<script>
function validate(){ var p=document.querySelector('input[type=password]').value; if(p.length<6){alert('Password must be at least 6 chars');return false;} return true;}
</script>
</body></html>

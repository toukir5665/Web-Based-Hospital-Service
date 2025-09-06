<?php
require '../config.php';
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
// handle add/edit/delete
if(isset($_GET['delete'])){
    $id=(int)$_GET['delete'];
    $pdo->prepare('DELETE FROM doctors WHERE doctor_id=?')->execute([$id]);
    header('Location: doctors.php'); exit;
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    $name=trim($_POST['name']); $spec=trim($_POST['specialization']); $avail=trim($_POST['availability']);
    if(isset($_POST['id']) && $_POST['id']){
        $id=(int)$_POST['id'];
        $pdo->prepare('UPDATE doctors SET name=?,specialization=?,availability=? WHERE doctor_id=?')->execute([$name,$spec,$avail,$id]);
    } else {
        $pdo->prepare('INSERT INTO doctors (name,specialization,availability) VALUES (?,?,?)')->execute([$name,$spec,$avail]);
    }
    header('Location: doctors.php'); exit;
}
$doctors = $pdo->query('SELECT * FROM doctors ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Manage Doctors</title><link rel="stylesheet" href="../assets/style.css"></head><body>
<div class="container"><h2>Manage Doctors</h2><p><a href="dashboard.php">Back</a></p>
<h3>Add / Edit Doctor</h3>
<form method="post">
  <input name="id" placeholder="(leave blank to add new)">
  <input name="name" placeholder="Name" required>
  <input name="specialization" placeholder="Specialization" required>
  <input name="availability" placeholder="Availability (e.g. Mon-Fri 9am-5pm)" required>
  <button class="btn" type="submit">Save</button>
</form>

<h3>Existing Doctors</h3>
<?php foreach($doctors as $d): ?>
  <div class="card">
    <strong><?php echo htmlspecialchars($d['name']); ?></strong> - <?php echo htmlspecialchars($d['specialization']); ?>
    <p><?php echo htmlspecialchars($d['availability']); ?></p>
    <p><a class="btn" href="doctors.php?delete=<?php echo $d['doctor_id']; ?>">Delete</a></p>
  </div>
<?php endforeach; ?>
</div></body></html>

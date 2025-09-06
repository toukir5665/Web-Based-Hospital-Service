<?php
require '../config.php';
if(!isset($_SESSION['user'])) { header('Location: ../login.php'); exit; }
$user = $_SESSION['user'];

// Handle booking from dashboard
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['book_appointment'])){
    $doctor_id = (int)$_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    if($doctor_id && $date && $time){
        $pdo->prepare('INSERT INTO appointments (user_id,doctor_id,date,time) VALUES (?,?,?,?)')
            ->execute([$user['user_id'],$doctor_id,$date,$time]);
        header('Location: dashboard.php?msg=booked');
        exit;
    } else {
        $error = 'Please choose doctor, date and time.';
    }
}

// Handle cancel by user
if(isset($_GET['action']) && $_GET['action']=='cancel' && isset($_GET['id'])){
    $id = (int)$_GET['id'];
    // only allow cancel if appointment belongs to user
    $pdo->prepare('UPDATE appointments SET status="cancelled" WHERE appointment_id=? AND user_id=?')
        ->execute([$id,$user['user_id']]);
    header('Location: dashboard.php');
    exit;
}

$appointments = $pdo->prepare('SELECT a.*, d.name as doctor_name FROM appointments a LEFT JOIN doctors d ON a.doctor_id=d.doctor_id WHERE a.user_id=? ORDER BY a.created_at DESC');
$appointments->execute([$user['user_id']]);
$appointments = $appointments->fetchAll(PDO::FETCH_ASSOC);

// fetch doctors for booking form
$doctors = $pdo->query('SELECT doctor_id,name,specialization FROM doctors ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>User Dashboard</title>
<link rel="stylesheet" href="../assets/style.css"></head><body>
<div class="container">
<h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
<p><a href="../index.php">Home</a> | <a href="../logout.php">Logout</a></p>

<h3>Book New Appointment</h3>
<?php if(isset($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post" onsubmit="return validateBooking();">
  <select name="doctor_id" required>
    <option value="">-- Select Doctor --</option>
    <?php foreach($doctors as $d): ?>
      <option value="<?php echo $d['doctor_id']; ?>"><?php echo htmlspecialchars($d['name'].' — '.$d['specialization']); ?></option>
    <?php endforeach; ?>
  </select>
  <input type="date" name="date" required>
  <input type="time" name="time" required>
  <input type="hidden" name="book_appointment" value="1">
  <button class="btn" type="submit">Request Appointment</button>
</form>

<h3>Your Appointments</h3>
<?php if(isset($_GET['msg']) && $_GET['msg']=='booked') echo '<p style="color:green">Appointment requested. Await admin approval.</p>'; ?>
<?php foreach($appointments as $a): ?>
  <div class="card"><strong><?php echo htmlspecialchars($a['doctor_name']); ?></strong>
    <p><?php echo htmlspecialchars($a['date']); ?> at <?php echo htmlspecialchars($a['time']); ?> - <em><?php echo $a['status']; ?></em></p>
    <?php if($a['status']!='cancelled'): ?>
      <p><a class="btn" href="dashboard.php?action=cancel&id=<?php echo $a['appointment_id']; ?>">Cancel Appointment</a></p>
    <?php endif; ?>
    <?php if($a['status']=='approved'): ?>
      <form method="post" action="leave_review.php">
        <input type="hidden" name="doctor_id" value="<?php echo $a['doctor_id']; ?>">
        <textarea name="comment" placeholder="Leave a review"></textarea>
        <select name="rating"><option>5</option><option>4</option><option>3</option><option>2</option><option>1</option></select>
        <button class="btn" type="submit">Submit Review</button>
      </form>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
</div>

<script>
function validateBooking(){
  var d=document.querySelector('select[name=doctor_id]').value;
  var dt=document.querySelector('input[name=date]').value;
  var t=document.querySelector('input[name=time]').value;
  if(!d || !dt || !t){ alert('Please select doctor, date and time'); return false; }
  return true;
}
</script>
</body></html>

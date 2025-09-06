<?php
require '../config.php';
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }
if(isset($_GET['delete'])){ $id=(int)$_GET['delete']; $pdo->prepare('DELETE FROM blogs WHERE blog_id=?')->execute([$id]); header('Location: blogs.php'); exit; }
if($_SERVER['REQUEST_METHOD']=='POST'){
    $title=trim($_POST['title']); $content=trim($_POST['content']); $author=trim($_POST['author']);
    if(isset($_POST['id']) && $_POST['id']){
        $pdo->prepare('UPDATE blogs SET title=?,content=?,author=? WHERE blog_id=?')->execute([$title,$content,$author,(int)$_POST['id']]);
    } else {
        $pdo->prepare('INSERT INTO blogs (title,content,author) VALUES (?,?,?)')->execute([$title,$content,$author]);
    }
    header('Location: blogs.php'); exit;
}
$blogs = $pdo->query('SELECT * FROM blogs ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Manage Blogs</title><link rel="stylesheet" href="../assets/style.css"></head><body>
<div class="container"><h2>Manage Blogs</h2><p><a href="dashboard.php">Back</a></p>
<form method="post">
  <input name="id" placeholder="(leave blank to add)">
  <input name="title" placeholder="Title" required>
  <textarea name="content" placeholder="Content" required></textarea>
  <input name="author" placeholder="Author" required>
  <button class="btn" type="submit">Save Blog</button>
</form>
<h3>Existing Blogs</h3>
<?php foreach($blogs as $b): ?>
  <div class="card"><strong><?php echo htmlspecialchars($b['title']); ?></strong><p><?php echo htmlspecialchars(substr($b['content'],0,200)); ?>...</p>
    <p><a class="btn" href="blogs.php?delete=<?php echo $b['blog_id']; ?>">Delete</a></p></div>
<?php endforeach; ?>
</div></body></html>

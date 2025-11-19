<?php
// admin/rooms.php
require_once '../inc/auth.php';
require_admin();
require_once '../inc/db.php';

if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $d = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    $d->bind_param("i",$id);
    $d->execute();
    header("Location: rooms.php");
    exit;
}

$res = $conn->query("SELECT * FROM rooms ORDER BY id DESC");
$rooms = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Kelola Kamar</title><link rel="stylesheet" href="../styles.css"></head>
<body>
  <h2>Kelola Kamar</h2>
  <a href="rooms_add.php">Tambah Kamar</a> | <a href="index.php">Dashboard</a>
  <ul>
    <?php foreach($rooms as $r): ?>
      <li>
        <?=htmlspecialchars($r['title'])?> - Rp <?=number_format($r['price'],0,',','.')?> -
        <a href="rooms_edit.php?id=<?=$r['id']?>">Edit</a> |
        <a href="rooms.php?delete=<?=$r['id']?>" onclick="return confirm('Hapus?')">Hapus</a>
      </li>
    <?php endforeach; ?>
  </ul>
</body></html>

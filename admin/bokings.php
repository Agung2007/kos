<?php
// admin/bookings.php
require_once '../inc/auth.php';
require_admin();
require_once '../inc/db.php';

if(isset($_GET['action']) && isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    if($action === 'confirm'){
        $u = $conn->prepare("UPDATE bookings SET status='confirmed' WHERE id=?");
        $u->bind_param("i",$id);
        $u->execute();
    } elseif($action === 'cancel'){
        $u = $conn->prepare("UPDATE bookings SET status='cancelled' WHERE id=?");
        $u->bind_param("i",$id);
        $u->execute();
    }
    header("Location: bookings.php");
    exit;
}

$q = $conn->query("SELECT b.*, u.name as user_name, r.title as room_title FROM bookings b JOIN users u ON b.user_id=u.id JOIN rooms r ON b.room_id=r.id ORDER BY b.created_at DESC");
$bookings = $q->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Kelola Booking</title><link rel="stylesheet" href="../styles.css"></head>
<body>
  <h2>Kelola Booking</h2>
  <a href="index.php">Dashboard</a>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>User</th><th>Room</th><th>Periode</th><th>Status</th><th>Aksi</th></tr>
    <?php foreach($bookings as $b): ?>
      <tr>
        <td><?=$b['id']?></td>
        <td><?=htmlspecialchars($b['user_name'])?></td>
        <td><?=htmlspecialchars($b['room_title'])?></td>
        <td><?=$b['start_date']?> - <?=$b['end_date']?:'-'?></td>
        <td><?=$b['status']?></td>
        <td>
          <?php if($b['status'] !== 'confirmed'): ?>
            <a href="bookings.php?action=confirm&id=<?=$b['id']?>">Confirm</a> |
          <?php endif; ?>
          <?php if($b['status'] !== 'cancelled'): ?>
            <a href="bookings.php?action=cancel&id=<?=$b['id']?>">Cancel</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body></html>

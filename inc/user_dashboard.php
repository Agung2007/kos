<?php
// user_dashboard.php
require_once 'auth.php';
require_login();
require_once 'db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT b.*, r.title, r.price FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? ORDER BY b.created_at DESC");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Dashboard</title><link rel="stylesheet" href="styles.css"></head>
<body>
  <h2>Halo, <?=htmlspecialchars($_SESSION['name'] ?? 'User')?></h2>
  <p><a href="logout.php">Logout</a></p>
  <h3>Booking Saya</h3>
  <?php foreach($bookings as $b): ?>
    <div class="booking">
      <strong><?=htmlspecialchars($b['title'])?></strong><br>
      Periode: <?=$b['start_date']?> - <?=$b['end_date'] ?: '-'?><br>
      Status: <?=$b['status']?>
    </div>
  <?php endforeach; ?>
</body></html>

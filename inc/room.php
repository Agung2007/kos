<?php
// room.php
require_once 'db.php';
require_once 'auth.php';

if(!isset($_GET['id'])){ header('Location: index.php'); exit; }
$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i",$id);
$stmt->execute();
$res = $stmt->get_result();
$room = $res->fetch_assoc();
if(!$room){ header('Location: index.php'); exit; }

$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!is_logged_in()){
        header("Location: login.php");
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $start = $_POST['start_date'] ?? null;
    $end = $_POST['end_date'] ?? null;

    $ins = $conn->prepare("INSERT INTO bookings (user_id, room_id, start_date, end_date, status) VALUES (?,?,?,?, 'pending')");
    $ins->bind_param("iiss", $user_id, $id, $start, $end);
    if($ins->execute()){
        $notice = "Booking berhasil diajukan. Tunggu konfirmasi admin.";
    } else {
        $notice = "Gagal booking.";
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?=htmlspecialchars($room['title'])?></title><link rel="stylesheet" href="styles.css"></head>
<body>
  <a href="index.php">← Kembali</a>
  <h2><?=htmlspecialchars($room['title'])?></h2>
  <p><?=nl2br(htmlspecialchars($room['description']))?></p>
  <p>Harga: Rp <?=number_format($room['price'],0,',','.')?></p>
  <p>Fasilitas: <?=htmlspecialchars($room['facilities'])?></p>
  <p>Status: <?=htmlspecialchars($room['status'])?></p>

  <?php if($notice): ?><p class="notice"><?=$notice?></p><?php endif; ?>

  <?php if(isset($_SESSION['user_id'])): ?>
    <form method="post">
      <label>Mulai: <input type="date" name="start_date" required></label><br>
      <label>Akhir (opsional): <input type="date" name="end_date"></label><br>
      <button type="submit">Ajukan Booking</button>
    </form>
  <?php else: ?>
    <p><a href="login.php">Login</a> untuk booking.</p>
  <?php endif; ?>
</body>
</html>

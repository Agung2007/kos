<?php
// index.php
require_once 'db.php';
$query = $conn->query("SELECT * FROM rooms ORDER BY created_at DESC");
$rooms = $query->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Website Kos</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <h1>Daftar Kamar Kos</h1>
    <nav>
      <a href="index.php">Home</a> |
      <a href="register.php">Register</a> |
      <a href="login.php">Login</a>
    </nav>
  </header>

  <main>
    <?php foreach($rooms as $r): ?>
      <div class="room">
        <h3><?=htmlspecialchars($r['title'])?> — Rp <?=number_format($r['price'],0,',','.')?></h3>
        <p><?=nl2br(htmlspecialchars($r['description']))?></p>
        <p>Fasilitas: <?=htmlspecialchars($r['facilities'])?></p>
        <p>Status: <?=htmlspecialchars($r['status'])?></p>
        <a href="room.php?id=<?=$r['id']?>">Lihat & Booking</a>
      </div>
    <?php endforeach; ?>
  </main>
</body>
</html>

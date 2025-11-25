<?php
// room.php
require_once 'db.php';
require_once 'auth.php';

if(!isset($_GET['id'])){ 
    header('Location: index.php'); 
    exit; 
}

$id = (int)$_GET['id'];

// Ambil detail kamar
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i",$id);
$stmt->execute();
$res = $stmt->get_result();
$room = $res->fetch_assoc();

if(!$room){ 
    header('Location: index.php'); 
    exit; 
}

// Ambil semua gambar kamar
$imgStmt = $conn->prepare("SELECT * FROM rooms_images WHERE room_id = ?");
$imgStmt->bind_param("i", $id);
$imgStmt->execute();
$imgRes = $imgStmt->get_result();
$images = $imgRes->fetch_all(MYSQLI_ASSOC);

// Submit booking
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
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($room['title']) ?></title>
    <link rel="stylesheet" href="styles.css">

    <style>
        /* tambahan css gallery */
        .room-gallery {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .room-gallery img {
            width: 230px;
            height: 170px;
            object-fit: cover;
            border-radius: 7px;
            border: 1px solid #ccc;
        }

        .notice {
            background: #e0ffe0;
            padding: 10px;
            border-left: 4px solid green;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
  <a href="index.php">← Kembali</a>

  <h2><?= htmlspecialchars($room['title']) ?></h2>

  <!-- Tampilkan gambar kamar -->
  <?php if(!empty($images)): ?>
    <div class="room-gallery">
        <?php foreach($images as $img): ?>
            <img src="../uploads/rooms/<?= htmlspecialchars($img['image_path']) ?>" 
                 alt="Gambar Kamar">
        <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <p><?= nl2br(htmlspecialchars($room['description'])) ?></p>
  <p>Harga: Rp <?= number_format($room['price'],0,',','.') ?></p>
  <p>Fasilitas: <?= htmlspecialchars($room['facilities']) ?></p>
  <p>Status: <?= htmlspecialchars($room['status']) ?></p>

  <?php if($notice): ?>
      <p class="notice"><?= $notice ?></p>
  <?php endif; ?>

  <?php if(is_logged_in()): ?>

    <form method="post">
      <label>Mulai: 
        <input type="date" name="start_date" required>
      </label><br><br>

      <label>Akhir (opsional): 
        <input type="date" name="end_date">
      </label><br><br>

      <button type="submit">Ajukan Booking</button>
    </form>

  <?php else: ?>
      <p><a href="login.php">Login</a> untuk booking.</p>
  <?php endif; ?>

</body>
</html>

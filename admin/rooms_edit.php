<?php
// admin/rooms_add.php
require_once '../inc/auth.php';
require_admin();
require_once '../inc/db.php';

$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $price = $_POST['price'] ?: 0;
    $fac = $_POST['facilities'] ?: '';
    $status = $_POST['status'] ?: 'empty';

    $ins = $conn->prepare("INSERT INTO rooms (title,description,price,facilities,status) VALUES (?,?,?,?,?)");
    $ins->bind_param("ssiss", $title, $desc, $price, $fac, $status);
    if($ins->execute()) header("Location: rooms.php");
    else $err = "Gagal menambah.";
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Tambah Kamar</title><link rel="stylesheet" href="../styles.css"></head>
<body>
  <h2>Tambah Kamar</h2>
  <?php if($err) echo "<p class='error'>$err</p>"; ?>
  <form method="post">
    <label>Judul: <input name="title" required></label><br>
    <label>Deskripsi: <textarea name="description"></textarea></label><br>
    <label>Harga: <input name="price" type="number" step="0.01" required></label><br>
    <label>Fasilitas: <input name="facilities"></label><br>
    <label>Status:
      <select name="status">
        <option value="empty">Kosong</option>
        <option value="reserved">Reserved</option>
        <option value="occupied">Terisi</option>
      </select>
    </label><br>
    <button type="submit">Simpan</button>
  </form>
</body></html>

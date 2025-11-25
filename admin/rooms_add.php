<?php
require_once '../inc/auth.php';
require_admin();
require_once '../inc/db.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $desc = $_POST['description'];
    $price = $_POST['price'] ?: 0;
    $fac = $_POST['facilities'] ?: '';
    $status = $_POST['status'] ?: 'empty';

    // Insert kamar dulu
    $ins = $conn->prepare("INSERT INTO rooms (title,description,price,facilities,status) VALUES (?,?,?,?,?)");
    $ins->bind_param("ssiss", $title, $desc, $price, $fac, $status);

    if ($ins->execute()) {
        $room_id = $conn->insert_id; // ID kamar yang baru dibuat

        // ===== Upload Gambar =====
        if (!empty($_FILES['images']['name'][0])) {

            $uploadDir = "../uploads/rooms/";

            // Buat folder jika belum ada
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['name'] as $key => $filename) {

                $tmp_name = $_FILES['images']['tmp_name'][$key];

                if ($tmp_name == "") continue;

                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newName = uniqid("room_", true) . "." . $ext;
                $filePath = $uploadDir . $newName;

                if (move_uploaded_file($tmp_name, $filePath)) {

                    // Simpan ke tabel rooms_images
                    $saveImg = $conn->prepare("INSERT INTO rooms_images (room_id, image_path) VALUES (?, ?)");
                    $saveImg->bind_param("is", $room_id, $newName);
                    $saveImg->execute();
                }
            }
        }

        header("Location: rooms.php");
        exit;
    } else {
        $err = "Gagal menambah.";
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tambah Kamar</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <h2>Tambah Kamar</h2>
  <?php if($err) echo "<p class='error'>$err</p>"; ?>

  <form method="post" enctype="multipart/form-data">
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

    <label>Gambar:
      <input type="file" name="images[]" multiple accept="image/*">
      <br><small>Bisa upload lebih dari 1 gambar</small>
    </label><br>

    <button type="submit">Simpan</button>
  </form>

</body>
</html>

<?php
// admin_register.php
require_once '../inc/db.php';
session_start();

$err = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if(!$name || !$email || !$pass){
        $err = "Harap isi semua data.";
    } else {
        // Cek apakah email sudah ada
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s",$email);
        $check->execute();
        $exist = $check->get_result()->num_rows;

        if($exist > 0){
            $err = "Email sudah terdaftar.";
        } else {
            // Daftarkan admin
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,'admin')");
            $stmt->bind_param("sss",$name,$email,$hash);

            if($stmt->execute()){
                $success = "Admin berhasil dibuat! Silakan login.";
            } else {
                $err = "Gagal membuat admin.";
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register Admin</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h2>Register Admin</h2>

  <?php if($err): ?><p class="error"><?= $err ?></p><?php endif; ?>
  <?php if($success): ?><p class="notice"><?= $success ?></p><?php endif; ?>

  <form method="post">
      <label>Nama:<br>
        <input name="name" required>
      </label><br><br>

      <label>Email:<br>
        <input type="email" name="email" required>
      </label><br><br>

      <label>Password:<br>
        <input type="password" name="password" required>
      </label><br><br>

      <button type="submit">Buat Admin</button>
  </form>

  <p><a href="login.php">Kembali ke Login</a></p>
</body>
</html>

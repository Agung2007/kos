<?php
// register.php
require_once 'db.php';
session_start();
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $phone = $_POST['phone'] ?? '';

    if(!$name || !$email || !$pass){ $err = "Lengkapi data."; }
    else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name,email,password,phone) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss",$name,$email,$hash,$phone);
        if($stmt->execute()){
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['role'] = 'user';
            header("Location: user_dashboard.php");
            exit;
        } else {
            $err = "Gagal register: mungkin email sudah terdaftar.";
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="styles.css"></head>
<body>
  <h2>Register</h2>
  <?php if($err) echo "<p class='error'>$err</p>"; ?>
  <form method="post">
    <label>Nama: <input name="name" required></label><br>
    <label>Email: <input name="email" type="email" required></label><br>
    <label>Password: <input name="password" type="password" required></label><br>
    <label>Phone: <input name="phone"></label><br>
    <button type="submit">Daftar</button>
  </form>
  <p>Sudah punya akun? <a href="login.php">Login</a></p>
</body></html>

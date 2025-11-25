k<?php
// login.php
require_once 'db.php';
session_start();
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role, name FROM users WHERE email = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if($res && password_verify($pass, $res['password'])){
        $_SESSION['user_id'] = $res['id'];
        $_SESSION['role'] = $res['role'];
        $_SESSION['name'] = $res['name'];
        if($res['role'] === 'admin') header("Location: admin/index.php");
        else header("Location: user_dashboard.php");
        exit;
    } else {
        $err = "Email atau password salah.";
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="styles.css"></head>
<body>
  <h2>Login</h2>
  <?php if($err) echo "<p class='error'>$err</p>"; ?>
  <form method="post">
    <label>Email: <input name="email" type="email" required></label><br>
    <label>Password: <input name="password" type="password" required></label><br>
    <button type="submit">Login</button>
  </form>
  <p>Belum punya akun? <a href="register.php">Register</a></p>
</body></html>

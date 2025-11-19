<?php
session_start();
require_once __DIR__ . '/../inc/db.php'; // mysqli connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['username']; // karena input name="username"
    $password = $_POST['password'];

    // Query admin
    $sql = "SELECT * FROM users WHERE name = ? AND role = 'admin' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {

        $_SESSION['user_id']  = $admin['id'];
        $_SESSION['name']     = $admin['name'];   // pakai 'name'
        $_SESSION['role']     = $admin['role'];

        header("Location: index.php");
        exit;

    } else {
        $error = "Nama atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
</head>
<body>

<h2>Login Admin</h2>

<?php if(isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    <label>Nama Admin</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

</body>
</html>

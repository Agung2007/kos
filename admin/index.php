<?php
// admin/index.php
require_once '../inc/auth.php';
require_admin();
require_once '../inc/db.php';
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Dashboard</title><link rel="stylesheet" href="../styles.css"></head>
<body>
  <h2>Admin Dashboard</h2>
  <nav>
    <a href="rooms.php">Kelola Kamar</a> |
    <a href="bookings.php">Kelola Booking</a> |
    <a href="../logout.php">Logout</a>
  </nav>
</body></html>

<?php
require 'db.php';
session_start();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $msg = "Fill in both fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $msg = "Invalid username or password.";
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
</head>
<body>
  <h2>Admin Login</h2>
  <?php if($msg) echo "<p style='color:red;'>" . htmlspecialchars($msg) . "</p>"; ?>
  <form method="post">
    <label>Username<br><input name="username" required></label><br><br>
    <label>Password<br><input type="password" name="password" required></label><br><br>
    <button type="submit">Login</button>
  </form>
</body>
</html>

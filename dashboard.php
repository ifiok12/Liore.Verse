<?php
require 'db.php';
session_start();

// ✅ Block access if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$msg = '';

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cap_name = trim($_POST['cap_name'] ?? '');
    $owner_name = trim($_POST['owner_name'] ?? '');

    if ($cap_name === '' || $owner_name === '') {
        $msg = "Please fill in both fields.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO caps (cap_name, owner_name) VALUES (?, ?)");
        $stmt->execute([$cap_name, $owner_name]);
        $msg = "✅ Cap added successfully!";
    }
}

// ✅ Fetch all caps
$stmt = $pdo->query("SELECT * FROM caps ORDER BY created_at DESC");
$caps = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
</head>
<body>
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
  <p><a href="logout.php">Logout</a></p>

  <h3>Add a New Cap</h3>
  <?php if($msg) echo "<p>" . htmlspecialchars($msg) . "</p>"; ?>
  <form method="post">
    <label>Cap Name<br><input name="cap_name" required></label><br><br>
    <label>Owner Name<br><input name="owner_name" required></label><br><br>
    <button type="submit">Add Cap</button>
  </form>

 <h3>All Caps</h3>
<table border="1" cellpadding="8">
  <tr>
    <th>ID</th>
    <th>Cap Name</th>
    <th>Owner Name</th>
    <th>Date Added</th>
    <th>Actions</th>
  </tr>
  <?php foreach($caps as $cap): ?>
  <tr>
    <td><?php echo $cap['id']; ?></td>
    <td><?php echo htmlspecialchars($cap['cap_name']); ?></td>
    <td><?php echo htmlspecialchars($cap['owner_name']); ?></td>
    <td><?php echo $cap['created_at']; ?></td>
    <td>
      <!-- Delete button -->
      <a href="delete_cap.php?id=<?php echo $cap['id']; ?>" 
         onclick="return confirm('Are you sure you want to delete this cap?');">
         Delete
      </a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>

</body>
</html>

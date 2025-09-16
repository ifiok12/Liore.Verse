<?php
require 'db.php';
session_start();

// Block if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Check if ID was sent
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM caps WHERE id = ?");
    $stmt->execute([$id]);
}

// After deleting, go back to dashboard
header("Location: dashboard.php");
exit;

<?php

session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$plan = $_POST['plan'] ?? '';
$tokens = 0;
switch ($plan) {
    case 'free': $tokens = 10; break;
    case 'dev': $tokens = 100; break;
    case 'studio': $tokens = 1000; break;
    default: header('Location: pricing.php'); exit;
}
$conn = getDbConnection();
$stmt = $conn->prepare('UPDATE users SET bundle = ?, tokens = ? WHERE id = ?');
$stmt->bind_param('sii', $plan, $tokens, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$conn->close();
$_SESSION['bundle'] = $plan;
$_SESSION['tokens'] = $tokens;
header('Location: user_dashboard.php');
exit;

<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$bundle = $_POST['bundle'] ?? null;
if (!$bundle) {
    header('Location: choose_bundle.php');
    exit;
}
$tokens = 0;
switch ($bundle) {
    case 'studio': $tokens = 1000; break;
    case 'adev': $tokens = 100; break;
    case 'free': $tokens = 10; break;
    default: $tokens = 0;
}

$conn = getDbConnection();
$stmt = $conn->prepare('UPDATE users SET bundle = ?, tokens = ? WHERE id = ?');
$stmt->bind_param('sii', $bundle, $tokens, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$conn->close();
$_SESSION['bundle'] = $bundle;
$_SESSION['tokens'] = $tokens;
header('Location: user_dashboard.php');
exit;

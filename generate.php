<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = $_POST['prompt'] ?? '';
    $quality = $_POST['quality'] ?? 'medium';
    $token_cost = ($quality === 'high') ? 5 : 2;

    
    $conn = getDbConnection();
    $stmt = $conn->prepare('SELECT tokens FROM users WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($db_tokens);
    $stmt->fetch();
    $stmt->close();
    if ($db_tokens < $token_cost) {
        $conn->close();
        header('Location: user_dashboard.php?error=notokens');
        exit;
    }

    
    $fake_path = 'fake_generation_' . date('Ymd_His') . '.png';
    $stmt = $conn->prepare('INSERT INTO generations (user_id, file_path, prompt, quality, created_at) VALUES (?, ?, ?, ?, NOW())');
    $stmt->bind_param('isss', $user_id, $fake_path, $prompt, $quality);
    $stmt->execute();
    $stmt->close();

    
    $stmt = $conn->prepare('UPDATE users SET tokens = tokens - ? WHERE id = ?');
    $stmt->bind_param('ii', $token_cost, $user_id);
    $stmt->execute();
    $stmt->close();

    
    $stmt = $conn->prepare('SELECT tokens FROM users WHERE id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($new_tokens);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    $_SESSION['tokens'] = $new_tokens;
    header('Location: user_dashboard.php?gen=success');
    exit;
}
?>

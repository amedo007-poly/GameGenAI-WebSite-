<?php
// login_process.php (registration logic)
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        header('Location: register.php?error=password');
        exit;
    }

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to DB
    $conn = getDbConnection();
    // Check if email already exists
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        header('Location: register.php?error=email');
        exit;
    }
    $stmt->close();

    // Insert user
    $stmt = $conn->prepare('INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $firstname, $lastname, $email, $hashed_password);
    if ($stmt->execute()) {
        session_start();
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['email'] = $email;
        header('Location: choose_bundle.php');
        exit;
    } else {
        header('Location: register.php?error=server');
        exit;
    }
} else {
    header('Location: register.php');
    exit;
}

<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['email'] ?? '') !== 'admin@gamegenai.com') {
    header('Location: login.php');
    exit;
}
$conn = getDbConnection();

if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
    $stmt->bind_param('i', $del_id);
    $stmt->execute();
    $stmt->close();
    header('Location: admin.php');
    exit;
}

$res = $conn->query('SELECT COUNT(*) FROM users');
$user_count = $res->fetch_row()[0];

$res = $conn->query('SELECT id, firstname, lastname, email, bundle, tokens, created_at FROM users ORDER BY created_at DESC');
$users = $res->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<style>
.dashboard-container {
  background: #232e2b;
  border-radius: 18px;
  box-shadow: 0 4px 24px #00ffaa22;
  padding: 40px 30px 60px 30px;
  max-width: 1100px;
  margin: 60px auto;
  color: #e6fefb;
}
.dashboard-container h2 {
  color: #7aff7a;
  font-size: 2.1rem;
  margin-bottom: 10px;
  letter-spacing: -1px;
}
.dashboard-container p {
  color: #b8f5c2;
}
.dashboard-container table {
  width: 100%;
  background: #1a2327;
  color: #fff;
  border-radius: 10px;
  margin-top: 18px;
  font-size: 1rem;
  box-shadow: 0 2px 12px #00ffaa22;
}
.dashboard-container th, .dashboard-container td {
  padding: 8px 10px;
  border-bottom: 1px solid #333;
}
.dashboard-container th {
  color: #7aff7a;
  background: #232e2b;
}
.dashboard-container tr:last-child td {
  border-bottom: none;
}
</style>
<?php include 'templates/header.php'; ?>
<section class="dashboard-container">
  <h2>Admin - Gestion des utilisateurs</h2>
  <p>Nombre total d'utilisateurs : <strong><?php echo $user_count; ?></strong></p>
  <table style="width:100%;background:#181818;color:#fff;border-radius:8px;">
    <tr><th>ID</th><th>Nom</th><th>Email</th><th>Offre</th><th>Jetons</th><th>Inscrit le</th><th>Action</th></tr>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?php echo $u['id']; ?></td>
        <td><?php echo htmlspecialchars($u['firstname'] . ' ' . $u['lastname']); ?></td>
        <td><?php echo htmlspecialchars($u['email']); ?></td>
        <td><?php echo htmlspecialchars($u['bundle']); ?></td>
        <td><?php echo (int)$u['tokens']; ?></td>
        <td><?php echo htmlspecialchars($u['created_at']); ?></td>
        <td><a href="admin.php?delete=<?php echo $u['id']; ?>" onclick="return confirm('Supprimer cet utilisateur ?');" style="color:#ff3333;">Supprimer</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>
<?php include 'templates/footer.php'; ?>

<?php
session_start();
if (!isset($_SESSION['bundle']) || $_SESSION['bundle'] !== 'adev') {
    header('Location: choose_bundle.php');
    exit;
}
$tokens = $_SESSION['tokens'] ?? 0;
?>
<style>
.dashboard-container {
  background: #232e2b;
  border-radius: 18px;
  box-shadow: 0 4px 24px #00ffaa22;
  padding: 40px 30px 60px 30px;
  max-width: 900px;
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
</style>
<?php include("templates/header.php"); ?>
<section class="dashboard-container">
  <h2>Bienvenue sur votre tableau de bord Adev</h2>
  <p>Vous avez <strong><?php echo $tokens; ?></strong> jetons pour utiliser le modèle.</p>
  <p>Plus de fonctionnalités pour les développeurs indépendants.</p>
</section>
<?php include("templates/footer.php"); ?>

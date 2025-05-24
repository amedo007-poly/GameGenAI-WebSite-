<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (isset($_SESSION['user_id'])) {
    
    $conn = getDbConnection();
    $stmt = $conn->prepare('SELECT bundle FROM users WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($bundle);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
    if ($bundle) {
        header('Location: user_dashboard.php');
        exit;
    }
}
?>
<?php include("templates/header.php"); ?>
<link rel="stylesheet" href="assets/style.css">
<section class="content-section pricing-section" style="margin-top:60px;">
  <h2 style="color:#66ffcc;">Choisissez votre offre</h2>
  <div class="pricing-cards">
    <div class="pricing-card free">
      <div class="pricing-icon"><span style="font-size:2.5rem;">🆓</span></div>
      <h3>Gratuit</h3>
      <p class="price">0€</p>
      <ul>
        <li>10 jetons offerts</li>
        <li>Génération standard</li>
        <li>Accès limité</li>
      </ul>
      <form action="dashboard.php" method="post">
        <input type="hidden" name="bundle" value="free">
        <button type="submit" class="btn">Choisir</button>
      </form>
    </div>
    <div class="pricing-card dev">
      <div class="pricing-icon"><span style="font-size:2.5rem;">💻</span></div>
      <h3>Développeur</h3>
      <p class="price">19€/mois</p>
      <ul>
        <li>100 jetons/mois</li>
        <li>Génération haute qualité</li>
        <li>Support prioritaire</li>
      </ul>
      <form action="dashboard.php" method="post">
        <input type="hidden" name="bundle" value="adev">
        <button type="submit" class="btn">Choisir</button>
      </form>
    </div>
    <div class="pricing-card studio">
      <div class="pricing-icon"><span style="font-size:2.5rem;">🏢</span></div>
      <h3>Studio</h3>
      <p class="price">49€/mois</p>
      <ul>
        <li>1000 jetons/mois</li>
        <li>Génération ultra qualité</li>
        <li>Accès API & intégrations</li>
      </ul>
      <form action="dashboard.php" method="post">
        <input type="hidden" name="bundle" value="studio">
        <button type="submit" class="btn">Choisir</button>
      </form>
    </div>
  </div>
</section>
<?php include("templates/footer.php"); ?>
<style>
body {
  background: #181c1f;
  color: #e6e6e6;
  font-family: 'Segoe UI', Arial, sans-serif;
}
.pricing-section {
  background: #20262b;
  border-radius: 16px;
  box-shadow: 0 4px 24px #0005;
  padding: 40px 0 60px 0;
}
.pricing-cards {
  display: flex;
  gap: 32px;
  justify-content: center;
  margin-top: 40px;
  flex-wrap: wrap;
}
.pricing-card {
  background: #23272a;
  border-radius: 12px;
  box-shadow: 0 2px 12px #0004;
  padding: 32px 24px;
  min-width: 260px;
  max-width: 320px;
  text-align: center;
  transition: transform 0.2s;
}
.pricing-card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 8px 32px #00ffaa33;
}
.pricing-icon {
  margin-bottom: 18px;
}
.price {
  font-size: 2rem;
  color: #66ffcc;
  margin: 12px 0 18px 0;
}
.pricing-card ul {
  list-style: none;
  padding: 0;
  margin: 0 0 18px 0;
}
.pricing-card ul li {
  margin-bottom: 8px;
  font-size: 1.08rem;
}
.btn {
  background: #66ffcc;
  color: #181c1f;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  padding: 10px 24px;
  cursor: pointer;
  transition: background 0.2s;
}
.btn:hover {
  background: #00ffaa;
}
</style>

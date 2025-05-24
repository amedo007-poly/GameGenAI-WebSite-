<?php
session_start();

$plan = $_POST['plan'] ?? '';
$selected_offer = isset($_GET['offer']) ? $_GET['offer'] : null;
$plans = [
  'free' => ['name' => 'Gratuit', 'price' => '0€', 'desc' => '10 jetons offerts, accès limité'],
  'dev' => ['name' => 'Développeur', 'price' => '19€/mois', 'desc' => '100 jetons/mois, haute qualité'],
  'studio' => ['name' => 'Studio', 'price' => '49€/mois', 'desc' => '1000 jetons/mois, ultra qualité'],
];
if (!isset($plans[$plan])) {
  header('Location: pricing.php');
  exit;
}
include 'templates/header.php';
?>
<section class="content-section" style="max-width:500px;margin:60px auto 0;">
  <h2>Paiement pour l'offre <?php echo $plans[$plan]['name']; ?></h2>
  <p style="color:#66ffcc;font-size:1.2rem;">Prix : <?php echo $plans[$plan]['price']; ?></p>
  <p><?php echo $plans[$plan]['desc']; ?></p>
  <form action="payment_success.php" method="post" style="margin-top:30px;">
    <input type="hidden" name="plan" value="<?php echo htmlspecialchars($plan); ?>">
    <label>Numéro de carte</label><br>
    <input type="text" name="card" required style="width:100%;padding:10px;margin-bottom:12px;"><br>
    <label>Expiration</label><br>
    <input type="text" name="exp" required placeholder="MM/AA" style="width:100px;padding:10px;margin-bottom:12px;">
    <label>CVV</label><br>
    <input type="text" name="cvv" required style="width:80px;padding:10px;margin-bottom:18px;">
    <br>
    <button type="submit" class="btn">Payer</button>
  </form>
</section>
<?php include 'templates/footer.php'; ?>

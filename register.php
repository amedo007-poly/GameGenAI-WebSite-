<?php include("templates/header.php"); ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id']) && (isset($_GET['offer']) || isset($_SESSION['selected_offer']))) {
    $offer = isset($_GET['offer']) ? $_GET['offer'] : $_SESSION['selected_offer'];
    unset($_SESSION['selected_offer']);
    header('Location: checkout.php?offer=' . urlencode($offer));
    exit;
}
?>

<section class="login-container">
  <h2>Créer un compte</h2>
  <?php if (isset($_GET['error']) && $_GET['error'] === 'password') : ?>
    <div class="error-line">Les mots de passe ne correspondent pas.</div>
  <?php endif; ?>
  <form action="login_process.php" method="post" onsubmit="return validatePasswords();">
    <div class="form-group">
      <label for="firstname">Prénom :</label>
      <input type="text" id="firstname" name="firstname" required>
    </div>

    <div class="form-group">
      <label for="lastname">Nom :</label>
      <input type="text" id="lastname" name="lastname" required>
    </div>

    <div class="form-group">
      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group">
      <label for="confirm_password">Confirmer le mot de passe :</label>
      <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn">S'inscrire</button>
    <a href="login.php" class="signin-link">Déjà un compte ? Se connecter</a>
  </form>
</section>

<?php include("templates/footer.php"); ?>
<script>
function validatePasswords() {
  const password = document.getElementById('password').value;
  const confirm = document.getElementById('confirm_password').value;
  if (password !== confirm) {
    // Show error line dynamically
    let errorDiv = document.querySelector('.error-line');
    if (!errorDiv) {
      errorDiv = document.createElement('div');
      errorDiv.className = 'error-line';
      errorDiv.innerText = 'Les mots de passe ne correspondent pas.';
      const form = document.querySelector('form');
      form.parentNode.insertBefore(errorDiv, form);
    }
    return false;
  } else {
    let errorDiv = document.querySelector('.error-line');
    if (errorDiv) errorDiv.remove();
  }
  return true;
}
</script>
<style>
.login-container {
  max-width: 400px;
  margin: 120px auto;
  background-color: #1b1b1b;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
  color: #fff;
  box-shadow: 0 0 10px rgba(102, 255, 204, 0.1);
}

.login-container h2 {
  color: #66ffcc;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 20px;
  text-align: left;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  color: #ccc;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"] {
  width: 100%;
  padding: 10px;
  background: #111;
  border: 1px solid #444;
  border-radius: 5px;
  color: #fff;
}

.form-group input[type="checkbox"] {
  width: auto;
  margin-right: 5px;
}

.btn {
  background: #66ffcc;
  color: #000;
  padding: 10px 20px;
  border: none;
  font-weight: bold;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s ease;
}

.btn:hover {
  background: #4ddbb2;
}

.signin-link {
  display: block;
  text-align: center;
  margin-top: 10px;
  color: #007bff;
  text-decoration: underline;
  cursor: pointer;
}

.error-line {
  color: #ff3333;
  background: none;
  border: none;
  text-align: center;
  margin-bottom: 10px;
  font-size: 1rem;
}
</style>
<?php

if (isset($_SESSION['selected_offer'])) {
    $offer = $_SESSION['selected_offer'];
    unset($_SESSION['selected_offer']);
    header('Location: checkout.php?offer=' . urlencode($offer));
    exit;
}
?>
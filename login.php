<?php include("templates/header.php"); ?>

<section class="login-container">
  <h2>Connexion</h2>
  <form action="auth/process_login.php" method="POST">
    <div class="form-group">
      <label for="username">Nom d'utilisateur</label>
      <input type="text" id="username" name="username" required>
    </div>

    <div class="form-group">
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required>
    </div>

    <button type="submit" class="btn">Se connecter</button>

  <a href="register.php" class="signin-link">Créer un compte ?</a> 
</form>
</section>

<?php include("templates/footer.php"); ?>

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

.form-group input {
  width: 100%;
  padding: 10px;
  background: #111;
  border: 1px solid #444;
  border-radius: 5px;
  color: #fff;
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
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: user_dashboard.php');
    exit;
}
include 'templates/header.php'; ?>
<link rel="stylesheet" href="assets/style.css">
<?php
?>

<section class="hero" style="padding: 120px 20px 60px 20px; background: linear-gradient(145deg, #111, #1b1b1b);">
  <h1 style="font-size:3.2rem;letter-spacing:-1px;">GameGenAI</h1>
  <p style="font-size:1.3rem;max-width:600px;margin:20px auto 0;">Générez des personnages, environnements, dialogues et plus pour vos jeux vidéo grâce à l’IA. Simple, rapide, intelligent. Prêt pour Unity & Unreal.</p>
  <a href="register.php" class="btn" style="font-size:1.1rem;padding:14px 36px;margin-top:30px;">Commencer</a>
  <div id="demo" style="margin:40px auto 0;max-width:700px;">
    <h3 style="color:#66ffcc;">Voir une démo&nbsp;: Génération d'assets IA</h3>
    <div style="position:relative;    padding-bottom:56.25%;    height:0;overflow:hidden;   border-radius:12px;    box-shadow:0 2px 16px #00ffaa33;">
      <iframe width="700" height="394" src="https://www.youtube.com/embed/1hHMwLxN6EM" title="Générer des modèles IA pour jeux vidéo" frameborder="0" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>
    </div>
  </div>
</section>


<section class="slider" style="margin-top:40px;">
  <div class="slides">                                     
    <div class="slide">
      <img src="assets/images/slider1-new.jpg" alt="Slide 1">
      <div class="slide-text">🧠 Génération de personnages</div>
    </div>
    <div class="slide">
      <img src="assets/images/slider2-new.jpg" alt="Slide 2">
      <div class="slide-text">🌍 Environnements procéduraux</div>
    </div>
    <div class="slide">
      <img src="assets/images/slider3-new.jpg" alt="Slide 3">
      <div class="slide-text">📜 Dialogues adaptatifs</div>
    </div>
    <div class="slide">
      <img src="assets/images/slider4-new.jpg" alt="Slide 4">
      <div class="slide-text">🎮 Intégration Unity & Unreal</div>
    </div>
    <div class="slide">
      <img src="assets/images/slider5-new.jpg" alt="Slide 5">
      <div class="slide-text">🔁 Apprentissage continu</div>
    </div>
  </div>
</section>


<section class="content-section" id="features" style="margin-top:60px;">
  <h2>Fonctionnalités Clés</h2>
  <div style="display:flex;flex-wrap:wrap;gap:40px;justify-content:center;margin-top:30px;">
    <div style="flex:1 1 220px;min-width:220px;max-width:320px;background:#181818;padding:24px 18px;border-radius:10px;">
      <h3 style="color:#66ffcc;font-size:1.2rem;">Génération IA</h3>
      <p>Créez des personnages, environnements et dialogues uniques en quelques clics.</p>
    </div>
    <div style="flex:1 1 220px;min-width:220px;max-width:320px;background:#181818;padding:24px 18px;border-radius:10px;">
      <h3 style="color:#66ffcc;font-size:1.2rem;">Intégration Facile</h3>
      <p>Exportez vos assets pour Unity, Unreal et d’autres moteurs de jeux.</p>
    </div>
    <div style="flex:1 1 220px;min-width:220px;max-width:320px;background:#181818;padding:24px 18px;border-radius:10px;">
      <h3 style="color:#66ffcc;font-size:1.2rem;">Rapide & Sécurisé</h3>
      <p>Interface moderne, responsive et sécurisée pour tous les studios.</p>
    </div>
  </div>
</section>


<section class="content-section" style="margin-top:40px;">
  <h2>Pourquoi GameGenAI ?</h2>
  <p style="max-width:700px;margin:0 auto;">GameGenAI simplifie la création de contenu pour les jeux vidéo grâce à l’IA. Testez gratuitement, découvrez la puissance de l’automatisation, et passez à la vitesse supérieure avec nos offres Premium et Pro.</p>
</section>


<section class="content-section" style="margin-top:40px;background:#151515;">
  <h2>Découvrez nos offres</h2>
  <p>Comparez les plans et choisissez celui qui correspond à vos besoins.</p>
  <a href="pricing.php" class="btn" style="margin-top:18px;">Voir les offres</a>
</section>


<div id="chatbot-bubble" style="position:fixed;bottom:30px;right:30px;z-index:9999;">
  <div id="chatbot-header" style="background:#66ffcc;color:#222;padding:10px 18px;border-radius:10px 10px 0 0;cursor:pointer;box-shadow:0 2px 8px #0003;">
    💬 GameGenAI Chatbot
  </div>
  <div id="chatbot-popup" style="display:none;background:#181818;padding:18px 12px 12px 12px;border-radius:0 0 10px 10px;box-shadow:0 2px 16px #0006;width:340px;max-width:90vw;">
    <div id="chat-history" style="height:220px;overflow-y:auto;background:#222;padding:8px 6px 8px 6px;border-radius:6px;color:#fff;font-size:0.98rem;margin-bottom:10px;"></div>
    <div style="display:flex;gap:6px;">
      <input type="text" id="chat-input" placeholder="Votre question..." style="flex:1;padding:7px 8px;border-radius:5px;border:1px solid #444;background:#222;color:#fff;">
      <button class="btn" onclick="sendChatMessage()">Envoyer</button>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
const chatbotHeader = document.getElementById('chatbot-header');
const chatbotPopup = document.getElementById('chatbot-popup');
const chatHistory = document.getElementById('chat-history');
const chatInput = document.getElementById('chat-input');

// Load chat history from localStorage (per user)
const userKey = 'chat_history_' + (window?.userId || 'guest');
function loadChatHistory() {
  const history = localStorage.getItem(userKey);
  chatHistory.innerHTML = '';
  if (history) {
    const messages = JSON.parse(history);
    messages.forEach(msg => {
      const div = document.createElement('div');
      div.innerHTML = `<b>${msg.role === 'user' ? 'Vous' : 'Bot'}:</b> ` + marked.parseInline(msg.content);
      div.style.marginBottom = '6px';
      chatHistory.appendChild(div);
    });
    chatHistory.scrollTop = chatHistory.scrollHeight;
  }
}
function saveChatMessage(role, content) {
  let history = localStorage.getItem(userKey);
  history = history ? JSON.parse(history) : [];
  history.push({role, content});
  localStorage.setItem(userKey, JSON.stringify(history));
}
function sendChatMessage() {
  const input = chatInput.value.trim();
  if (!input) return;
  saveChatMessage('user', input);
  loadChatHistory();
  chatInput.value = '';
  chatHistory.innerHTML += '<div><b>Bot:</b> <span style="color:#aaa">Réflexion en cours...</span></div>';
  chatHistory.scrollTop = chatHistory.scrollHeight;
  fetch('https://openrouter.ai/api/v1/chat/completions', {
    method: 'POST',
    headers: {
      Authorization: 'Bearer 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',
      'HTTP-Referer': 'https://www.sitename.com',
      'X-Title': 'SiteName',
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      model: 'deepseek/deepseek-r1:free',
      messages: JSON.parse(localStorage.getItem(userKey) || '[]').map(m => ({role: m.role, content: m.content})),
    }),
  })
  .then(r => r.json())
  .then(data => {
    const reply = data.choices?.[0]?.message?.content || 'Aucune réponse reçue.';
    saveChatMessage('bot', reply);
    loadChatHistory();
  })
  .catch(e => {
    saveChatMessage('bot', 'Erreur: ' + e.message);
    loadChatHistory();
  });
}
chatbotHeader.onclick = function() {
  chatbotPopup.style.display = chatbotPopup.style.display === 'none' ? 'block' : 'none';
  if (chatbotPopup.style.display === 'block') loadChatHistory();
};
window.addEventListener('DOMContentLoaded', loadChatHistory);
</script>

<?php include 'templates/footer.php'; ?>
<script src="assets/script.js"></script>
<script>

const pricingBtn = document.querySelector('a.btn[href="pricing.php"]');
if (pricingBtn) {
  pricingBtn.addEventListener('click', function(e) {
    window.location.href = 'pricing.php';
  });
}
</script>
<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

$conn = getDbConnection();
$stmt = $conn->prepare('SELECT firstname, tokens FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $tokens);
$stmt->fetch();
$stmt->close();

$gens = [];
$stmt = $conn->prepare('SELECT file_path, prompt, quality, created_at FROM generations WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($file_path, $prompt, $quality, $created_at);
while ($stmt->fetch()) {
    $gens[] = compact('file_path', 'prompt', 'quality', 'created_at');
}
$stmt->close();
$conn->close();
?>
<?php include 'templates/header.php'; ?>
<style>
.dashboard-container {
  background: linear-gradient(135deg, #181c20 0%, #23272b 100%);
  border-radius: 18px;
  box-shadow: 0 4px 32px #000a;
  padding: 38px 32px 32px 32px;
  max-width: 820px;
  margin: 40px auto 0 auto;
  color: #e6e6e6;
}
.dashboard-container h2 {
  color: #66ffcc;
  font-size: 2.1rem;
  margin-bottom: 10px;
  letter-spacing: -1px;
}
.dashboard-container h3 {
  color: #fff;
  margin-top: 32px;
  margin-bottom: 12px;
  font-size: 1.25rem;
  letter-spacing: -0.5px;
}
.dashboard-container p {
  color: #b8c2cc;
}
.dashboard-container form {
  background: #23272b;
  border-radius: 12px;
  padding: 22px 18px 18px 18px;
  box-shadow: 0 2px 12px #0004;
  margin-bottom: 32px;
}
.dashboard-container label {
  color: #8be9fd;
  font-weight: 500;
}
.dashboard-container input[type="file"],
.dashboard-container textarea,
.dashboard-container select {
  background: #181c20;
  color: #e6e6e6;
  border: 1px solid #333b44;
  border-radius: 6px;
  padding: 8px;
  margin-top: 4px;
}
.dashboard-container textarea {
  resize: vertical;
}
.dashboard-container button.btn {
  background: linear-gradient(90deg, #66ffcc 0%, #1de9b6 100%);
  color: #181818;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  padding: 10px 28px;
  font-size: 1.08rem;
  box-shadow: 0 2px 8px #0003;
  transition: background 0.2s, color 0.2s;
}
.dashboard-container button.btn:hover {
  background: linear-gradient(90deg, #1de9b6 0%, #66ffcc 100%);
  color: #111;
}
.dashboard-container #token-cost {
  color: #66ffcc;
  font-weight: bold;
}
.dashboard-container table {
  width: 100%;
  background: linear-gradient(90deg, #23272b 60%, #181c20 100%);
  color: #e6e6e6;
  border-radius: 10px;
  box-shadow: 0 2px 12px #0004;
  margin-top: 10px;
  border-collapse: separate;
  border-spacing: 0;
}
.dashboard-container th, .dashboard-container td {
  padding: 10px 8px;
  text-align: left;
}
.dashboard-container th {
  background: #23272b;
  color: #66ffcc;
  font-weight: 600;
  border-bottom: 2px solid #1de9b6;
}
.dashboard-container tr:nth-child(even) td {
  background: #202428;
}
.dashboard-container tr:nth-child(odd) td {
  background: #23272b;
}
.dashboard-container a {
  color: #1de9b6;
  text-decoration: underline;
}
.dashboard-container a:hover {
  color: #66ffcc;
}

.chatbot-section {
  background: linear-gradient(135deg, #181c20 0%, #23272b 100%);
  border-radius: 14px;
  box-shadow: 0 2px 16px #0007;
  padding: 28px 24px 18px 24px;
  margin-top: 40px;
  color: #e6e6e6;
}
.chatbot-section h2 {
  color: #66ffcc;
  font-size: 1.4rem;
  margin-bottom: 10px;
}
.chatbot-section .chat-container {
  background: #202428;
  border-radius: 8px;
  min-height: 80px;
  padding: 16px;
  color: #e6e6e6;
  margin-bottom: 10px;
}
.chatbot-section .input-group input {
  background: #181c20;
  color: #e6e6e6;
  border: 1px solid #333b44;
  border-radius: 6px;
  padding: 8px;
}
.chatbot-section .input-group button.btn {
  background: linear-gradient(90deg, #66ffcc 0%, #1de9b6 100%);
  color: #181818;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  padding: 8px 18px;
  font-size: 1.02rem;
  box-shadow: 0 2px 8px #0003;
  transition: background 0.2s, color 0.2s;
}
.chatbot-section .input-group button.btn:hover {
  background: linear-gradient(90deg, #1de9b6 0%, #66ffcc 100%);
  color: #111;
}
</style>
<section class="dashboard-container">
  <h2>Bienvenue, <?php echo htmlspecialchars($firstname); ?> !</h2>
  <p>Jetons restants : <strong><?php echo (int)$tokens; ?></strong></p>
  <?php if (isset($_GET['gen']) && $_GET['gen'] === 'success'): ?>
    <div style="color:#66ffcc;">Génération enregistrée avec succès !</div>
  <?php elseif (isset($_GET['error']) && $_GET['error'] === 'notokens'): ?>
    <div style="color:#ff3333;">Vous n'avez pas assez de jetons pour cette génération.</div>
  <?php endif; ?>
  <h3>Générer un asset</h3>
  <form action="generate.php" method="post" enctype="multipart/form-data" style="margin-bottom:30px;">
    <label for="fileUpload">Fichier de référence :</label>
    <input type="file" name="fileUpload" id="fileUpload"><br><br>
    <label for="prompt">Description :</label>
    <textarea name="prompt" id="prompt" rows="3" style="width:100%;"></textarea><br><br>
    <label for="quality">Qualité :</label>
    <select name="quality" id="quality" onchange="updateTokenCost()">
      <option value="high">Haute (5 jetons)</option>
      <option value="medium">Moyenne (2 jetons)</option>
    </select>
    <span id="token-cost" style="margin-left:10px;color:#66ffcc;font-weight:bold;">Coût : 5 jetons</span><br><br>
    <label for="model">Modèle IA :</label>
    <select name="model" id="model">
      <option value="sdxl">Stable Diffusion XL</option>
      <option value="minimax">Minimax image-01</option>
    </select><br><br>
    <button type="submit" class="btn">Générer</button>
  </form>
  <script>
  function updateTokenCost() {
    var q = document.getElementById('quality').value;
    document.getElementById('token-cost').innerText = 'Coût : ' + (q === 'high' ? '5 jetons' : '2 jetons');
  }
  </script>
  <h3>Historique de vos générations</h3>
  <table style="width:100%;background:#181818;color:#fff;border-radius:8px;">
    <tr><th>Date</th><th>Prompt</th><th>Qualité</th><th>Fichier</th></tr>
    <?php foreach ($gens as $gen): ?>
      <tr>
        <td><?php echo htmlspecialchars($gen['created_at']); ?></td>
        <td><?php echo htmlspecialchars($gen['prompt']); ?></td>
        <td><?php echo htmlspecialchars($gen['quality']); ?></td>
        <td><?php if ($gen['file_path']): ?><a href="<?php echo htmlspecialchars($gen['file_path']); ?>" target="_blank">Voir</a><?php else: ?>—<?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>

<section class="chatbot-section" style="max-width:700px;margin:40px auto 0;">
  <h2>Votre assistant IA</h2>
  <div id="response" class="chat-container" style="background:#181818;padding:20px;border-radius:8px;min-height:80px;"></div>
  <div class="input-group" style="display:flex;gap:10px;margin-top:10px;">
    <input type="text" class="form-control" id="userInput" placeholder="Posez votre question..." style="flex:1;padding:10px;border-radius:5px;border:1px solid #444;background:#222;color:#fff;">
    <button class="btn" onclick="sendMessage()">Demander !</button>
  </div>
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


const userKey = 'chat_history_' + (<?php echo json_encode($user_id); ?>);
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

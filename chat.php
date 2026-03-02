<?php
include("config/auth.php");
include("config/connect.php");
include("include/header.php");
include("include/menu.php");

$my_id = $_SESSION['users']['id'];

// Get parameters from URL (if coming from an ad)
$target_annonce_id = isset($_GET['annonce_id']) ? intval($_GET['annonce_id']) : null;
$target_seller_id = isset($_GET['seller_id']) ? intval($_GET['seller_id']) : null;

// Fetch conversations
// We want the last message of each unique conversation (annonce_id + other_user_id)
$conv_query = "SELECT m.*, a.titre as annonce_titre, 
               CASE WHEN m.expediteur_id = $my_id THEN m.destinataire_id ELSE m.expediteur_id END as other_user_id,
               u.nom as other_user_name
               FROM messages m
               JOIN annonces a ON m.annonce_id = a.ida
               JOIN users u ON u.idu = (CASE WHEN m.expediteur_id = $my_id THEN m.destinataire_id ELSE m.expediteur_id END)
               WHERE (m.expediteur_id = $my_id OR m.destinataire_id = $my_id)
               AND m.idm IN (
                   SELECT MAX(idm) FROM messages 
                   WHERE expediteur_id = $my_id OR destinataire_id = $my_id 
                   GROUP BY annonce_id, 
                   CASE WHEN expediteur_id = $my_id THEN destinataire_id ELSE expediteur_id END
               )
               ORDER BY m.date_envoi DESC";

$conv_result = mysqli_query($id, $conv_query);
$conversations = [];
while ($row = mysqli_fetch_assoc($conv_result)) {
    $conversations[] = $row;
}

// Check if we need to start a NEW conversation
if ($target_annonce_id && $target_seller_id && $target_seller_id != $my_id) {
    $exists = false;
    foreach ($conversations as $c) {
        if ($c['annonce_id'] == $target_annonce_id && $c['other_user_id'] == $target_seller_id) {
            $exists = true;
            break;
        }
    }
    
    if (!$exists) {
        // Fetch ad and seller info for the UI
        $ad_q = mysqli_query($id, "SELECT titre FROM annonces WHERE ida = $target_annonce_id");
        $user_q = mysqli_query($id, "SELECT nom FROM users WHERE idu = $target_seller_id");
        if ($ad_q && $user_q) {
            $ad_info = mysqli_fetch_assoc($ad_q);
            $user_info = mysqli_fetch_assoc($user_q);
            if ($ad_info && $user_info) {
                // Add a "virtual" conversation at the top
                array_unshift($conversations, [
                    'annonce_id' => $target_annonce_id,
                    'other_user_id' => $target_seller_id,
                    'other_user_name' => $user_info['nom'],
                    'annonce_titre' => $ad_info['titre'],
                    'contenu' => 'Nouvelle conversation...',
                    'virtual' => true
                ]);
            }
        }
    }
}
?>

<style>
.chat-container { height: 80vh; margin-top: 20px; margin-bottom: 20px; }
.sidebar { background: #fff; border-right: 1px solid #ddd; overflow-y: auto; }
.chat-list-item { padding: 15px; cursor: pointer; border-bottom: 1px solid #eee; transition: 0.3s; }
.chat-list-item:hover { background: #f8f9fa; }
.chat-list-item.active { background: #e9ecef; border-left: 5px solid #ff8800; }
.chat-list-item .annonce-title { font-weight: bold; color: #333; display: block; }
.chat-list-item .user-name { font-size: 0.85em; color: #666; }
.chat-list-item .last-msg { font-size: 0.8em; color: #999; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.chat-area { background: #fff; display: flex; flex-direction: column; }
.chat-header { background: #ff8800; color: white; padding: 15px; font-weight: bold; }
.chat-messages { flex-grow: 1; overflow-y: auto; padding: 20px; background: #f4f7f6; display: flex; flex-direction: column; height: 50vh; }
.message { margin-bottom: 15px; max-width: 80%; padding: 10px 15px; border-radius: 20px; position: relative; }
.message.sent { align-self: flex-end; background: #ff8800; color: white; border-bottom-right-radius: 2px; }
.message.received { align-self: flex-start; background: #fff; color: #333; border-bottom-left-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
.message .time { font-size: 0.7em; display: block; margin-top: 5px; opacity: 0.7; }

.chat-input { padding: 15px; border-top: 1px solid #eee; }
.btn-orange { background: #ff8800; color: white; border: none; }
.btn-orange:hover { background: #e67a00; color: white; }
</style>

<div class="container chat-container shadow rounded overflow-hidden mt-5">
    <div class="row h-100">
        <!-- Sidebar -->
        <div class="col-md-4 sidebar p-0 d-none d-md-block">
            <div class="p-3 border-bottom bg-light">
                <h5 class="mb-0">Mes Discussions</h5>
            </div>
            <div id="conversationList">
                <?php foreach ($conversations as $conv): ?>
                    <?php 
                        $isActive = ($target_annonce_id == $conv['annonce_id'] && $target_seller_id == $conv['other_user_id']);
                    ?>
                    <div class="chat-list-item <?php echo $isActive ? 'active' : ''; ?>" 
                         onclick="selectConversation(<?php echo $conv['annonce_id']; ?>, <?php echo $conv['other_user_id']; ?>, '<?php echo addslashes($conv['annonce_titre']); ?>', this)">
                        <span class="annonce-title"><?php echo htmlspecialchars($conv['annonce_titre']); ?></span>
                        <span class="user-name">Interlocuteur: <?php echo htmlspecialchars($conv['other_user_name']); ?></span>
                        <span class="last-msg"><?php echo htmlspecialchars($conv['contenu']); ?></span>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($conversations)): ?>
                    <div class="p-4 text-center text-muted">Auntune discussion pour le moment.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8 col-sm-12 d-flex flex-column p-0 bg-white">
            <div class="chat-header" id="chatHeader">
                <?php echo ($target_annonce_id && $target_seller_id) ? "Chat sur l'annonce" : "Sélectionnez une discussion"; ?>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="m-auto text-muted">Sélectionnez une conversation pour commencer.</div>
            </div>

            <div class="chat-input mt-auto">
                <form id="chatForm" onsubmit="event.preventDefault(); sendMessage();">
                    <div class="input-group">
                        <input type="text" id="messageInput" class="form-control rounded-pill-start" placeholder="Écrire votre message..." autocomplete="off">
                        <button type="submit" class="btn btn-orange rounded-pill-end px-4">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let currentAnnonceId = <?php echo $target_annonce_id ?: 'null'; ?>;
let currentOtherUserId = <?php echo $target_seller_id ?: 'null'; ?>;
let refreshInterval = null;
const myId = <?php echo $my_id; ?>;

function selectConversation(annonceId, otherUserId, title, element) {
    currentAnnonceId = annonceId;
    currentOtherUserId = otherUserId;
    
    // UI update
    document.querySelectorAll('.chat-list-item').forEach(el => el.classList.remove('active'));
    if(element) element.classList.add('active');
    document.getElementById('chatHeader').innerText = "Annonce : " + title;
    
    loadMessages();
    
    // Auto-refresh every 3 seconds
    if (refreshInterval) clearInterval(refreshInterval);
    refreshInterval = setInterval(loadMessages, 3000);
}

function loadMessages() {
    if (!currentAnnonceId || !currentOtherUserId) return;
    
    fetch(`fetch_messages.php?annonce_id=${currentAnnonceId}&other_user_id=${currentOtherUserId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('chatMessages');
                let html = "";
                data.messages.forEach(msg => {
                    const type = msg.expediteur_id == myId ? 'sent' : 'received';
                    html += `
                        <div class="message ${type}">
                            ${msg.contenu}
                            <span class="time">${msg.date_formatted}</span>
                        </div>
                    `;
                });
                container.innerHTML = html;
                container.scrollTop = container.scrollHeight;
            }
        });
}

function sendMessage() {
    const input = document.getElementById('messageInput');
    const text = input.value.trim();
    if (!text || !currentAnnonceId || !currentOtherUserId) return;
    
    const formData = new FormData();
    formData.append('annonce_id', currentAnnonceId);
    formData.append('destinataire_id', currentOtherUserId);
    formData.append('contenu', text);
    
    fetch('send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = "";
            loadMessages();
        } else {
            alert(data.message);
        }
    });
}

// Initial load if target is set
document.addEventListener('DOMContentLoaded', () => {
    if (currentAnnonceId && currentOtherUserId) {
        // Find the active item in list
        const activeItem = document.querySelector('.chat-list-item.active');
        if (activeItem) {
            const title = activeItem.querySelector('.annonce-title').innerText;
            selectConversation(currentAnnonceId, currentOtherUserId, title, activeItem);
        } else {
            // Virtual conversation title fetch
            loadMessages();
            refreshInterval = setInterval(loadMessages, 3000);
        }
    }
});
</script>

<?php
include("include/footer.php");
?>

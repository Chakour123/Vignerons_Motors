<?php
include("config/connect.php");
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['users']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit();
}

if (isset($_GET['annonce_id']) && isset($_GET['other_user_id'])) {
    $my_id = $_SESSION['users']['id'];
    $annonce_id = intval($_GET['annonce_id']);
    $other_user_id = intval($_GET['other_user_id']);
    
    // Mark messages as read
    mysqli_query($id, "UPDATE messages SET lu = TRUE 
                       WHERE annonce_id = $annonce_id 
                       AND expediteur_id = $other_user_id 
                       AND destinataire_id = $my_id");

    $query = "SELECT * FROM messages 
              WHERE annonce_id = $annonce_id 
              AND ((expediteur_id = $my_id AND destinataire_id = $other_user_id) 
                   OR (expediteur_id = $other_user_id AND destinataire_id = $my_id))
              ORDER BY date_envoi ASC";
              
    $result = mysqli_query($id, $query);
    $messages = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $row['date_formatted'] = date("H:i", strtotime($row['date_envoi']));
        $messages[] = $row;
    }
    
    echo json_encode(['success' => true, 'messages' => $messages]);
} else {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
}
?>

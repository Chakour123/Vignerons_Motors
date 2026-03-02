<?php
include("config/connect.php");
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['users']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['annonce_id']) && isset($_POST['destinataire_id']) && isset($_POST['contenu'])) {
    $my_id = $_SESSION['users']['id'];
    $annonce_id = intval($_POST['annonce_id']);
    $destinataire_id = intval($_POST['destinataire_id']);
    $contenu = mysqli_real_escape_string($id, $_POST['contenu']);
    
    if (empty($contenu)) {
        echo json_encode(['success' => false, 'message' => 'Message vide']);
        exit();
    }

    $query = "INSERT INTO messages (annonce_id, expediteur_id, destinataire_id, contenu) 
              VALUES ($annonce_id, $my_id, $destinataire_id, '$contenu')";
              
    if (mysqli_query($id, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi : ' . mysqli_error($id)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
}
?>

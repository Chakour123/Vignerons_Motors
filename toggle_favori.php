<?php
include("config/connect.php");
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['users']['id'])) {
    echo json_encode(['success' => false, 'message' => 'Veuillez vous connecter pour ajouter des favoris.']);
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_SESSION['users']['id'];
    $ada = intval($_GET['id']);
    
    // Check if it's already a favorite
    $check_query = "SELECT idf FROM favoris WHERE user_id = $user_id AND annonce_id = $ada";
    $result = mysqli_query($id, $check_query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        // Remove from favorites
        $delete_query = "DELETE FROM favoris WHERE user_id = $user_id AND annonce_id = $ada";
        if (mysqli_query($id, $delete_query)) {
            echo json_encode(['success' => true, 'action' => 'removed']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
        }
    } else {
        // Add to favorites
        $insert_query = "INSERT INTO favoris (user_id, annonce_id) VALUES ($user_id, $ada)";
        if (mysqli_query($id, $insert_query)) {
            echo json_encode(['success' => true, 'action' => 'added']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID invalide.']);
}
?>

<?php
include("config/connect.php");

header('Content-Type: application/json');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $ida = intval($_GET['id']);
    
    // Increment view count
    mysqli_query($id, "UPDATE annonces SET vues = vues + 1 WHERE ida = $ida");
    
    $query = "SELECT a.*, c.nom as category_name FROM annonces a 
              JOIN categories c ON a.categorie_id = c.idc 
              WHERE a.ida = $ida LIMIT 1";
    
    $result = mysqli_query($id, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $ad = mysqli_fetch_assoc($result);
        
        // Check if ad is favorited by current user
        $is_favorite = false;
        session_start();
        if (isset($_SESSION['users']['id'])) {
            $user_id = $_SESSION['users']['id'];
            $fav_query = "SELECT idf FROM favoris WHERE user_id = $user_id AND annonce_id = $ida LIMIT 1";
            $fav_result = mysqli_query($id, $fav_query);
            if ($fav_result && mysqli_num_rows($fav_result) > 0) {
                $is_favorite = true;
            }
        }
        
        // Format price
        $ad['prix_formatted'] = number_format($ad['prix'], 2, ',', ' ') . ' €';
        echo json_encode(['success' => true, 'data' => $ad, 'is_favorite' => $is_favorite]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Annonce non trouvée.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID invalide.']);
}
?>

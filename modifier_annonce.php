<?php
include("config/auth.php");
include("config/connect.php");

$user_id = $_SESSION['users']['id'];
$error = "";
$success = "";

if (!isset($_GET['ida'])) {
    header("Location: crud.php");
    exit();
}

$ida_param = intval($_GET['ida']);

// Fetch existing ad details
$is_admin = (isset($_SESSION['users']['role']) && $_SESSION['users']['role'] == 2);
if ($is_admin) {
    $query = "SELECT * FROM annonces WHERE ida = $ida_param";
} else {
    $query = "SELECT * FROM annonces WHERE ida = $ida_param AND user_id = $user_id";
}
$result = mysqli_query($id, $query);
$ad = ($result) ? mysqli_fetch_assoc($result) : null;

if (!$ad) {
    header("Location: crud.php");
    exit();
}

include("include/header.php");
include("include/menu.php");

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_ad'])) {
    $titre = mysqli_real_escape_string($id, $_POST['titre']);
    $prix = mysqli_real_escape_string($id, $_POST['prix']);
    $categorie_id = intval($_POST['categorie_id']);
    $description = mysqli_real_escape_string($id, $_POST['description']);
    $image_name = $ad['image'];

    // Handle Image Upload (Optional for update)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        if ($_FILES['photo']['size'] > 2097152) { // 2MB
            $error = "L'image est trop lourde (max 2 Mo).";
        } else {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $image_name = uniqid() . "." . $ext;
                $upload_path = "uploads/" . $image_name;
                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                    $image_name = $ad['image'];
                    $error = "Erreur lors du téléchargement de l'image.";
                }
            } else {
                $error = "Format d'image non supporté.";
            }
        }
    }

    if (empty($error)) {
        $update_query = "UPDATE annonces SET 
                         titre = '$titre', 
                         description = '$description', 
                         prix = '$prix', 
                         categorie_id = $categorie_id, 
                         image = '$image_name' 
                         WHERE ida = $ida_param" . ($is_admin ? "" : " AND user_id = $user_id");
        
        if (mysqli_query($id, $update_query)) {
            $success = "Annonce mise à jour avec succès !";
            // Refresh ad data
            $ad['titre'] = $titre;
            $ad['prix'] = $prix;
            $ad['categorie_id'] = $categorie_id;
            $ad['description'] = $description;
            $ad['image'] = $image_name;
        } else {
            $error = "Erreur lors de la mise à jour : " . mysqli_error($id);
        }
    }
}

// Fetch Categories for dropdown
$cat_query = "SELECT * FROM categories";
$cat_result = mysqli_query($id, $cat_query);
?>

<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Modifier l'annonce</h1>
</div>

<div class="container-fluid bg-light overflow-hidden py-5">
    <div class="container py-5">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-8 col-xl-6 wow fadeInUp" data-wow-delay="0.1s">
                <form action="modifier_annonce.php?ida=<?php echo $ida_param; ?>" method="POST" enctype="multipart/form-data">
                    <h3 class="mb-4" style="font-style: italic;">Modifier votre annonce</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-item w-100">
                                <label class="form-label my-3">Titre<sup>*</sup></label>
                                <input type="text" name="titre" class="form-control" value="<?php echo htmlspecialchars($ad['titre']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item w-100">
                                <label class="form-label my-3">Prix<sup>*</sup></label>
                                <input type="number" name="prix" class="form-control" value="<?php echo $ad['prix']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Catégorie<sup>*</sup></label>
                        <select name="categorie_id" id="categorie_id" class="form-select" required>
                            <?php while($cat = mysqli_fetch_assoc($cat_result)): ?>
                                <option value="<?php echo $cat['idc']; ?>" <?php echo ($cat['idc'] == $ad['categorie_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nom']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Image (laisser vide pour conserver l'actuelle)</label>
                        <input type="file" name="photo" accept="image/*" class="form-control">
                        <div class="mt-2 text-muted">
                            Image actuelle : <img src="uploads/<?php echo $ad['image']; ?>" width="50" alt="">
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Description<sup>*</sup></label>
                        <textarea name="description" class="form-control" spellcheck="false" cols="30" rows="8" required><?php echo htmlspecialchars($ad['description']); ?></textarea>
                    </div>
                    <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                        <div class="col-6">
                            <a href="crud.php" class="btn btn-secondary border-secondary py-3 px-4 text-uppercase w-100">Retour</a>
                        </div>
                        <div class="col-6">
                            <button type="submit" name="update_ad" class="btn btn-primary border-secondary py-3 px-4 text-uppercase w-100">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include("include/footer.php");
?>

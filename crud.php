<?php
include("config/auth.php");
include("config/connect.php");
include("include/header.php");
include("include/menu.php");

$user_id = $_SESSION['users']['id'];
$error = "";
$success = "";

if (isset($_GET['delete'])) {
    $ida = intval($_GET['delete']);
    $delete_query = "DELETE FROM annonces WHERE ida = $ida AND user_id = $user_id";
    if (mysqli_query($id, $delete_query)) {
        $success = "Annonce supprimée avec succès !";
    } else {
        $error = "Erreur lors de la suppression : " . mysqli_error($id);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_ad'])) {
    $titre = mysqli_real_escape_string($id, $_POST['titre']);
    $prix = mysqli_real_escape_string($id, $_POST['prix']);
    $categorie_id = intval($_POST['categorie_id']);
    $description = mysqli_real_escape_string($id, $_POST['description']);
    $date_publication = date("Y-m-d H:i:s");
    
    $image_name = "";

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        if ($_FILES['photo']['size'] > 2097152) { 
            $error = "L'image est trop lourde (max 2 Mo).";
        } else {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $image_name = uniqid() . "." . $ext;
                $upload_path = "uploads/" . $image_name;
                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                    $image_name = "";
                    $error = "Erreur lors du téléchargement de l'image.";
                }
            } else {
                $error = "Format d'image non supporté.";
            }
        }
    } else {
        $error = "Veuillez sélectionner une image.";
    }

    if (empty($error)) {
        $insert_query = "INSERT INTO annonces (titre, description, prix, categorie_id, user_id, image, date_publication) 
                         VALUES ('$titre', '$description', '$prix', '$categorie_id', '$user_id', '$image_name', '$date_publication')";
        
        if (mysqli_query($id, $insert_query)) {
            $success = "Annonce créée avec succès !";
        } else {
            $error = "Erreur lors de la création : " . mysqli_error($id);
        }
    }
}

$cat_query = "SELECT * FROM categories";
$cat_result = mysqli_query($id, $cat_query);

$ads_query = "SELECT a.*, c.nom FROM annonces a 
              JOIN categories c ON a.categorie_id = c.idc 
              WHERE a.user_id = $user_id 
              ORDER BY a.date_publication DESC";
$ads_result = mysqli_query($id, $ads_query);

?>

<div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Déposer une annonce</h1>
    </div>



    <div class="container-fluid bg-light overflow-hidden py-5">
        <div class="container py-5">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="row g-5">
                <div class="col-md-12 col-lg-5 col-xl-5 wow fadeInUp" data-wow-delay="0.1s">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <h3 class="mb-4 wow fadeInUp" style="font-style: italic;" data-wow-delay="0.1s">Déposer une annonce</h3>
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Titre<sup>*</sup></label>
                                    <input type="text" name="titre" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Prix<sup>*</sup></label>
                                    <input type="number" name="prix" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Catégorie<sup>*</sup></label>
                            <select name="categorie_id" id="categorie_id" class="form-select" required>
                                <option value="">Choisir une catégorie</option>
                                <?php while($cat = mysqli_fetch_assoc($cat_result)): ?>
                                    <option value="<?php echo $cat['idc']; ?>"><?php echo htmlspecialchars($cat['nom']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Image<sup>*</sup></label>
                            <input type="file" name="photo" accept="image/*" class="form-control" required>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Description<sup>*</sup></label>
                            <textarea name="description" class="form-control" spellcheck="false" cols="30" rows="11"
                                placeholder="description de l'engin" required></textarea>
                        </div>
                        <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                            <button type="submit" name="create_ad"
                                class="btn btn-primary border-secondary py-3 px-4 text-uppercase w-100">Déposer l'annonce</button>
                        </div>
                    </form>
                </div>
                    <div class="col-md-12 col-lg-7 col-xl-7 wow fadeInUp" data-wow-delay="0.3s">
                         <h3 class="mb-4 wow fadeInUp" style="font-style: italic;" data-wow-delay="0.1s">Mes annonces</h3>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col" class="text-start">Titre</th>
                                        <th scope="col">Catégorie</th>
                                        <th scope="col">Prix</th>
                                        <th scope="col">Modifier</th>
                                        <th scope="col">Supprimer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($ads_result) > 0): ?>
                                        <?php while($ad = mysqli_fetch_assoc($ads_result)): ?>
                                            <tr class="text-center">
                                                <td class="py-4 text-start"><?php echo htmlspecialchars($ad['titre']); ?></td>
                                                <td class="py-4"><?php echo htmlspecialchars($ad['nom']); ?></td>
                                                <td class="py-4"><?php echo number_format($ad['prix'], 2, ',', ' '); ?> €</td>
                                                <td class="py-4">
                                                    <a href="modifier_annonce.php?ida=<?php echo $ad['ida']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                                </td>
                                                <td class="py-4">
                                                    <a href="crud.php?delete=<?php echo $ad['ida']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">Vous n'avez pas encore d'annonces.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
        </div>
    </div>
    





<?php
include("include/footer.php");
?> 


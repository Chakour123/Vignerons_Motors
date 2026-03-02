<?php
include("config/auth.php");
include("config/connect.php");
include("include/header.php");
include("include/menu.php");

$user_id = $_SESSION['users']['id'];
$error = "";
$success = "";

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $nom = mysqli_real_escape_string($id, $_POST['nom']);
    $prenom = mysqli_real_escape_string($id, $_POST['prenom']);
    $email = mysqli_real_escape_string($id, $_POST['email']);
    
    $update_query = "UPDATE users SET nom = '$nom', prenom = '$prenom', email = '$email' WHERE idu = $user_id";
    
    // Handle Photo Update
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        if ($_FILES['photo']['size'] > 1048576) {
            $error = "La photo est trop lourde (max 1 Mo).";
        } else {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $photo_name = uniqid() . "." . $ext;
                $upload_path = "uploads/" . $photo_name;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                    $update_query = "UPDATE users SET nom = '$nom', prenom = '$prenom', email = '$email', photo = '$photo_name' WHERE idu = $user_id";
                }
            }
        }
    }

    if (empty($error)) {
        if (mysqli_query($id, $update_query)) {
            $success = "Profil mis à jour avec succès !";
            // Update session name if it changed
            $_SESSION['users']['nom'] = $nom;
        } else {
            $error = "Erreur lors de la mise à jour : " . mysqli_error($id);
        }
    }
}

// Fetch User Data
$query = "SELECT * FROM users WHERE idu = $user_id";
$result = mysqli_query($id, $query);
$user = mysqli_fetch_assoc($result);
$photo = !empty($user['photo']) ? $user['photo'] : 'default.png';
?>

    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Mon Profil</h1>
    </div>

    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-5 justify-content-center">
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded p-5 text-center shadow-sm">
                        <div class="mb-4">
                            <img src="uploads/<?php echo htmlspecialchars($photo); ?>" class="img-fluid rounded-circle border border-primary border-3" style="width: 200px; height: 200px; object-fit: cover;" alt="Photo de profil">
                        </div>
                        <h3 class="mb-2"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h3>
                        <p class="text-primary mb-4"><?php echo htmlspecialchars($user['email']); ?></p>
                        <div class="d-flex justify-content-center">
                            <a class="btn btn-square btn-primary rounded-circle mx-1"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle mx-1"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle mx-1"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="bg-light rounded p-5 shadow-sm">
                        <h4 class="mb-4 border-bottom pb-2">Modifier mes informations</h4>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form action="profil.php" method="POST" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="nom" id="nom" placeholder="Nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                                        <label for="nom">Nom</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="prenom" id="prenom" placeholder="Prénom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
                                        <label for="prenom">Prénom</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="photo" class="form-label text-dark">Changer ma photo de profil</label>
                                        <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit" name="update_profile">Enregistrer les modifications</button>
                                </div>
                                <div class="col-12 text-center mt-3">
                                    <a href="deconnexion.php" class="text-danger small"><i class="fas fa-sign-out-alt me-1"></i> Se déconnecter</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
include("include/footer.php");
?>

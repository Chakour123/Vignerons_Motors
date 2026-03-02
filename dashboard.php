<?php
include("config/auth.php");
include("config/connect.php");

// Security check: strictly role 2 for admins
if (!isset($_SESSION['users']['role']) || $_SESSION['users']['role'] != 2) {
    header("Location: index.php");
    exit();
}

$success = "";
$error = "";

// --- ACTIONS HANDLER ---

// Delete Ad
if (isset($_GET['delete_ad'])) {
    $ida = intval($_GET['delete_ad']);
    if (mysqli_query($id, "DELETE FROM annonces WHERE ida = $ida")) {
        $success = "Annonce supprimée.";
    } else {
        $error = "Erreur lors de la suppression de l'annonce.";
    }
}

// Delete User
if (isset($_GET['delete_user'])) {
    $idu = intval($_GET['delete_user']);
    // Prevent admin from deleting themselves
    if ($idu == $_SESSION['users']['id']) {
        $error = "Vous ne pouvez pas supprimer votre propre compte admin.";
    } else {
        if (mysqli_query($id, "DELETE FROM users WHERE idu = $idu")) {
            $success = "Utilisateur supprimé.";
        } else {
            $error = "Erreur lors de la suppression de l'utilisateur.";
        }
    }
}

// Category CRUD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_category'])) {
        $nom = mysqli_real_escape_string($id, $_POST['nom']);
        if (mysqli_query($id, "INSERT INTO categories (nom) VALUES ('$nom')")) {
            $success = "Catégorie ajoutée.";
        }
    }
    
    if (isset($_POST['edit_category'])) {
        $idc = intval($_POST['idc']);
        $nom = mysqli_real_escape_string($id, $_POST['nom']);
        if (mysqli_query($id, "UPDATE categories SET nom = '$nom' WHERE idc = $idc")) {
            $success = "Catégorie modifiée.";
        }
    }
    
    if (isset($_POST['delete_category'])) {
        $idc = intval($_POST['delete_category']);
        if (mysqli_query($id, "DELETE FROM categories WHERE idc = $idc")) {
            $success = "Catégorie supprimée.";
        } else {
            $error = "Impossible de supprimer : cette catégorie est probablement liée à des annonces.";
        }
    }
}

// --- DATA FETCHING ---

// Fetch all ads
$ads_q = mysqli_query($id, "SELECT a.*, u.nom as user_nom, c.nom as cat_nom FROM annonces a 
                            JOIN users u ON a.user_id = u.idu 
                            JOIN categories c ON a.categorie_id = c.idc 
                            ORDER BY a.date_publication DESC");

// Fetch all users
$users_q = mysqli_query($id, "SELECT * FROM users ORDER BY idu DESC");

// Fetch all categories
$cats_q = mysqli_query($id, "SELECT * FROM categories ORDER BY nom ASC");

include("include/header.php");
include("include/menu.php");
?>

<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">TABLEAU DE BORD ADMIN</h1>
</div>

<div class="container py-5">
    <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="ads-tab" data-bs-toggle="tab" data-bs-target="#ads" type="button" role="tab" aria-controls="ads" aria-selected="true">Annonces</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="false">Utilisateurs</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">Catégories</button>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content" id="adminTabsContent">
        
        <!-- Tab Annonces -->
        <div class="tab-pane fade show active" id="ads" role="tabpanel" aria-labelledby="ads-tab">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Vendeur</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($ad = mysqli_fetch_assoc($ads_q)): ?>
                            <tr>
                                <td><img src="uploads/<?php echo $ad['image']; ?>" width="60" class="rounded"></td>
                                <td><?php echo htmlspecialchars($ad['titre']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($ad['cat_nom']); ?></span></td>
                                <td><?php echo htmlspecialchars($ad['user_nom']); ?></td>
                                <td><?php echo number_format($ad['prix'], 2, ',', ' '); ?> €</td>
                                <td>
                                    <a href="modifier_annonce.php?ida=<?php echo $ad['ida']; ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                                    <a href="dashboard.php?delete_ad=<?php echo $ad['ida']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette annonce ?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Utilisateurs -->
        <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = mysqli_fetch_assoc($users_q)): ?>
                            <tr>
                                <td>#<?php echo $user['idu']; ?></td>
                                <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo $user['role'] == 2 ? 'Admin' : 'Utilisateur'; ?></td>
                                <td>
                                    <?php if($user['idu'] != $_SESSION['users']['id']): ?>
                                        <a href="dashboard.php?delete_user=<?php echo $user['idu']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Désinscrire cet utilisateur ?')"><i class="fas fa-user-slash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Catégories -->
        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">Ajouter une catégorie</div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="nom" class="form-control" required>
                                </div>
                                <button type="submit" name="add_category" class="btn btn-primary w-100">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Nom</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($cat = mysqli_fetch_assoc($cats_q)): ?>
                                    <tr>
                                        <td>
                                            <form method="POST" class="d-flex gx-2">
                                                <input type="hidden" name="idc" value="<?php echo $cat['idc']; ?>">
                                                <input type="text" name="nom" value="<?php echo htmlspecialchars($cat['nom']); ?>" class="form-control form-control-sm me-2">
                                                <button type="submit" name="edit_category" class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="delete_category" value="<?php echo $cat['idc']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette catégorie ?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include("include/footer.php"); ?>

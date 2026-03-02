<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['users']);
$user_name = $is_logged_in ? $_SESSION['users']['nom'] : '';
?>
    <div class="container-fluid nav-bar p-0">
        <div class="row gx-0 bg-primary px-5 align-items-center">
            <div class="col-lg-3 d-none d-lg-block">
                <nav class="navbar navbar-light position-relative" style="width: 250px;">
                    <button class="navbar-toggler border-0 fs-4 w-100 px-0 text-start" type="button"
                        data-bs-toggle="collapse" data-bs-target="#allCat">
                        <img src="img/mopao.png" width="70px" height="70px" alt="">
                    </button>
                </nav>
            </div>
            <div class="col-12 col-lg-9">
                <nav class="navbar navbar-expand-lg navbar-light bg-primary ">
                    <a class="navbar-brand d-block d-lg-none">
                        <h1 class="display-5 text-secondary m-0"><i
                                class="fas fa-shopping-bag text-white me-2"></i>Vignerons</h1>
                        
                    </a>
                    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars fa-1x"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav ms-auto py-0">
                            <a href="index.php" class="nav-item nav-link">Accueil</a>
                            <a href="annonce.php" class="nav-item nav-link">Annonces</a>
                            <a href="favoris.php" class="nav-item nav-link">Favoris</a>
                            <a href="chat.php" class="nav-item nav-link">Messagerie</a>
                            <a href="contact.php" class="nav-item nav-link me-2">Contact</a>
                            <?php if ($is_logged_in): ?>
                                <a href="profil.php" class="nav-item nav-link">
                                    <i class="fas fa-user me-1"> Profil</i>
                                </a>
                            <?php else: ?>
                                <a href="connexion.php" class="nav-item nav-link">Connexion</a>
                            <?php endif; ?>

                        </div>
                        <?php if (isset($_SESSION['users']['role']) && $_SESSION['users']['role'] == 2): ?>
                            <a href="dashboard.php" class="btn btn-secondary rounded-pill py-2 px-4 px-lg-3 mb-3 mb-md-3 mb-lg-0"><i
                                class="fas fa-cog me-2"></i> Admin</a>
                        <?php else: ?>
                            <a href="crud.php" class="btn btn-secondary rounded-pill py-2 px-4 px-lg-3 mb-3 mb-md-3 mb-lg-0"><i
                                class="fas fa-shopping-cart me-2"></i> Créer une annonce</a>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->
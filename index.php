<?php
session_start();
include("config/connect.php");
include("include/header.php");
include("include/menu.php");

$user_id = $_SESSION['users']['id'] ?? null;
$username = $_SESSION['users']['nom'] ?? null;

$random_ads_query = "SELECT a.*, c.nom as nom FROM annonces a 
                      JOIN categories c ON a.categorie_id = c.idc 
                      ORDER BY RAND() LIMIT 8";
$random_ads_result = mysqli_query($id, $random_ads_query);

$popular_ads_query = "SELECT a.*, c.nom as nom FROM annonces a 
                       JOIN categories c ON a.categorie_id = c.idc 
                       ORDER BY a.vues DESC LIMIT 5";
$popular_ads_result = mysqli_query($id, $popular_ads_query);
?>

    <div class="container-fluid carousel bg-light px-0">
        <div class="row g-0 justify-content-end">
            <div class="col-12 col-lg-7 col-xl-9">
                <div class="header-carousel owl-carousel bg-light py-5">
                    <div class="row g-0 header-carousel-item align-items-center">
                        <div class="col-xl-6 carousel-img wow fadeInLeft" data-wow-delay="0.1s">
                            <img src="img/ci1.png" class="img-fluid w-100" alt="Image">
                        </div>
                        <div class="col-xl-6 carousel-content p-4">
                            <h4 class="text-uppercase fw-bold mb-4 wow fadeInRight" data-wow-delay="0.1s"
                                style="letter-spacing: 3px;">Offres Exclusives Motos</h4>
                            <h1 class="display-3 text-capitalize mb-4 wow fadeInRight" data-wow-delay="0.3s">Conçue pour les routes difficiles et
                                 les longues sorties.</h1>
                            <p class="text-dark wow fadeInRight" data-wow-delay="0.5s">Promo : -15% immédiat</p>
                            <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight" data-wow-delay="0.7s"
                                href="annonce.php">Voir</a>
                        </div>
                    </div>
                    <div class="row g-0 header-carousel-item align-items-center">
                        <div class="col-xl-6 carousel-img wow fadeInLeft" data-wow-delay="0.1s">
                            <img src="img/ci2.png" class="img-fluid w-100" alt="Image">
                        </div>
                        <div class="col-xl-6 carousel-content p-4">
                            <h4 class="text-uppercase fw-bold mb-4 wow fadeInRight" data-wow-delay="0.1s"
                                style="letter-spacing: 3px;">Offres Exclusives Motos</h4>
                            <h1 class="display-3 text-capitalize mb-4 wow fadeInRight" data-wow-delay="0.3s">Puissance, vitesse et design 
                                agressif réunis.</h1>
                            <p class="text-dark wow fadeInRight" data-wow-delay="0.5s">Promo : -15% immédiat</p>
                            <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight" data-wow-delay="0.7s"
                                href="annonce.php">Voir</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5 col-xl-3 wow fadeInRight" data-wow-delay="0.1s">
                <div class="carousel-header-banner h-100">
                    <img src="img/hm.png" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="Image">
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Searvices Start -->
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                    <div class="d-inline-flex align-items-center">
                        <img src="img/moto.png" width="40px" height="50px" alt="">
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Motos</h6>
                            <p class="mb-0">Puissance, vitesse et sensations garanties!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.2s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <img src="img/scoot.png" width="40px" height="50px" alt="">
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Scooters</h6>
                            <p class="mb-0">Pratiques, économiques pour déplacements urbains</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.3s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <img src="img/velosi.png" width="40px" height="50px" alt="">
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Vélos classiques</h6>
                            <p class="mb-0">Simples, écologiques pour tous trajets</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.4s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <img src="img/veloel.png" width="40px" height="50px" alt="">
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Vélos électriques</h6>
                            <p class="mb-0">Assistance électrique pour trajets sans effort</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.5s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <img src="img/tro.png" width="40px" height="50px" alt="">
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Trotinettes</h6>
                            <p class="mb-0">Compactes, modernes pour mobilité rapide</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.6s">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <img src="img/quad.png" width="40px" height="50px" alt="">
                        <div class="ms-4">
                            <h6 class="text-uppercase mb-2">Quads</h6>
                            <p class="mb-0">Fun et adrénaline sur terrain privé</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Searvices End -->

    <!-- Products Offer Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <a class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                        <div>
                            <p class="text-muted mb-3">Offre spéciale pour vous!</p>
                            <h3 class="text-primary">Smart XioVo</h3>
                            <h1 class="display-3 text-secondary mb-0">- 40% </h1>
                        </div>
                        <img src="img/prim.png" class="img-fluid" alt="">
                    </a>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <a class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                        <div>
                            <p class="text-muted mb-3">Offre spéciale pour vous!</p>
                            <h3 class="text-primary">Pist Vespa</h3>
                            <h1 class="display-3 text-secondary mb-0">- 20% </h1>
                        </div>
                        <img src="img/deuxi.png" class="img-fluid" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid product py-5">
        <div class="container py-5">
            <div class="tab-class">
                <div class="row g-4">
                    <div class="col-lg-4 text-start wow fadeInLeft" data-wow-delay="0.1s">
                        <h1>Annonces</h1>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <?php if ($random_ads_result && mysqli_num_rows($random_ads_result) > 0): ?>
                                <?php while($ad = mysqli_fetch_assoc($random_ads_result)): ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="product-item rounded wow fadeInUp" data-wow-delay="0.1s">
                                            <div class="product-item-inner border rounded">
                                                <div class="product-item-inner-item">
                                                    <img src="uploads/<?php echo htmlspecialchars($ad['image']); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo htmlspecialchars($ad['titre']); ?>">
                                                    <div class="product-details">
                                                        <a href="javascript:void(0)" onclick="showAdDetails(<?php echo $ad['ida']; ?>)"><i class="fa fa-eye fa-1x"></i></a>
                                                    </div>
                                                </div>
                                                <div class="text-center rounded-bottom p-4">
                                                    <a class="d-block mb-2"><?php echo htmlspecialchars($ad['nom']); ?></a>
                                                    <a href="annonce_details.php?id=<?php echo $ad['ida']; ?>" class="d-block h4"><?php echo htmlspecialchars($ad['titre']); ?></a>
                                                    <span class="text-primary fs-5"><?php echo number_format($ad['prix'], 2, ',', ' '); ?> €</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12 text-center">
                                    <p>Aucune annonce pour le moment.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Products End -->

    <!-- Product Banner Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <a>
                        <div class="bg-primary rounded position-relative">
                            <img src="img/vel.png" class="img-fluid w-100 rounded" alt="">
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4"
                                style="background: rgba(255, 255, 255, 0.5);">
                                <h3 class="display-5 text-primary">Vélos électriques <br></h3>
                                <a href="annonce.php" class="btn btn-primary rounded-pill align-self-start py-2 px-4">Voir plus</a>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.2s">
                    <a>
                        <div class="text-center bg-primary rounded position-relative">
                            <img src="img/qu.png" class="img-fluid w-100" alt="">
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4"
                                style="background: rgba(242, 139, 0, 0.5);">
                                <h2 class="display-2 text-secondary">Quads</h2>
                                <a href="annonce.php" class="btn btn-secondary rounded-pill align-self-center py-2 px-4">Voir plus</a>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Banner End -->

    <!-- Product List Satrt -->
    <div class="container-fluid products productList overflow-hidden">
        <div class="container products-mini py-5">
            <div class="mx-auto text-center mb-5" style="max-width: 900px;">
                <h4 class="text-primary border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp"
                    data-wow-delay="0.1s">Populaires</h4>
                <h1 class="mb-0 display-3 wow fadeInUp" data-wow-delay="0.3s">Les annonces populaires</h1>
            </div>
            <div class="productList-carousel owl-carousel pt-4 wow fadeInUp" data-wow-delay="0.3s">
                <?php if ($popular_ads_result && mysqli_num_rows($popular_ads_result) > 0): ?>
                    <?php while($pad = mysqli_fetch_assoc($popular_ads_result)): ?>
                        <div class="productImg-item products-mini-item border">
                            <div class="row g-0">
                                <div class="col-5">
                                    <div class="products-mini-img border-end h-100">
                                        <img src="uploads/<?php echo htmlspecialchars($pad['image']); ?>" class="img-fluid w-100 h-100" alt="<?php echo htmlspecialchars($pad['titre']); ?>" style="object-fit: cover;">
                                        <div class="products-mini-icon rounded-circle bg-primary">
                                            <a href="javascript:void(0)" onclick="showAdDetails(<?php echo $pad['ida']; ?>)"><i class="fa fa-eye fa-1x text-white"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="products-mini-content p-3">
                                        <a class="d-block mb-2 text-muted"><?php echo htmlspecialchars($pad['nom']); ?></a>
                                        <a href="annonce_details.php?id=<?php echo $pad['ida']; ?>" class="d-block h4"><?php echo htmlspecialchars($pad['titre']); ?></a>
                                        <div class="mb-2">
                                            <small class="text-muted"><i class="fa fa-eye me-1"></i><?php echo $pad['vues']; ?> vues</small>
                                        </div>
                                        <span class="text-primary fs-5"><?php echo number_format($pad['prix'], 2, ',', ' '); ?> €</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center w-100">
                        <p>Aucune annonce populaire à afficher.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

  <?php
include("include/modal_details.php");
include("include/footer.php");
?> 


    

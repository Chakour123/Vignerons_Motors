<?php
include("config/auth.php");
include("config/connect.php");
include("include/header.php");
include("include/menu.php");

$user_id = $_SESSION['users']['id'];

// Fetch user's favorite ads
$query = "SELECT a.*, c.nom as category_name FROM annonces a 
          JOIN favoris f ON a.ida = f.annonce_id 
          JOIN categories c ON a.categorie_id = c.idc 
          WHERE f.user_id = $user_id 
          ORDER BY f.date_ajout DESC";

$result = mysqli_query($id, $query);

$popular_ads_query = "SELECT a.*, c.nom as nom FROM annonces a 
                       JOIN categories c ON a.categorie_id = c.idc 
                       ORDER BY a.vues DESC LIMIT 5";
$popular_ads_result = mysqli_query($id, $popular_ads_query);

?>

<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">MES FAVORIS</h1>
</div>

<div class="container-fluid product py-5">
    <div class="container py-5">
        <div class="tab-class">
            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="row g-4" id="fav-ads-container">
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($ad = mysqli_fetch_assoc($result)): ?>
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
                                                <a class="d-block mb-2"><?php echo htmlspecialchars($ad['category_name']); ?></a>
                                                <a href="javascript:void(0)" onclick="showAdDetails(<?php echo $ad['ida']; ?>)" class="d-block h4"><?php echo htmlspecialchars($ad['titre']); ?></a>
                                                <span class="text-primary fs-5"><?php echo number_format($ad['prix'], 2, ',', ' '); ?> €</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <div class="mb-4">
                                    <i class="fa fa-heart-broken fa-4x text-muted opacity-50"></i>
                                </div>
                                <h3>Vous n'avez pas encore de favoris.</h3>
                                <p class="text-muted">Explorez nos annonces et cliquez sur le cœur pour les retrouver ici !</p>
                                <a href="annonce.php" class="btn btn-primary rounded-pill py-3 px-5 mt-3">Voir les annonces</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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

<?php
include("config/connect.php");

// Pagination settings
$limit = 8;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Filter and Search
$category_filter = isset($_GET['category']) && is_numeric($_GET['category']) ? intval($_GET['category']) : null;
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($id, $_GET['search']) : '';

// Build Where Clause
$where_clauses = [];
if ($category_filter) {
    $where_clauses[] = "a.categorie_id = $category_filter";
}
if ($search_query) {
    $where_clauses[] = "(a.titre LIKE '%$search_query%' OR a.description LIKE '%$search_query%')";
}
$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Count total ads for pagination
$count_query = "SELECT COUNT(*) as total FROM annonces a $where_sql";
$count_result = mysqli_query($id, $count_query);
$total_ads = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_ads / $limit);

// Fetch ads for current page
$ads_query = "SELECT a.*, c.nom as nom FROM annonces a 
              JOIN categories c ON a.categorie_id = c.idc 
              $where_sql 
              ORDER BY a.date_publication DESC 
              LIMIT $limit OFFSET $offset";
$ads_result = mysqli_query($id, $ads_query);

if ($ads_result && mysqli_num_rows($ads_result) > 0): ?>
    <?php while($ad = mysqli_fetch_assoc($ads_result)): ?>
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
    
    <!-- Pagination -->
    <div class="col-12 text-center mt-5">
        <div class="pagination d-flex justify-content-center">
            <?php if ($page > 1): ?>
                <a href="#" data-page="<?php echo $page - 1; ?>" class="rounded pg-link">&laquo;</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="#" data-page="<?php echo $i; ?>" 
                   class="rounded pg-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="#" data-page="<?php echo $page + 1; ?>" class="rounded pg-link">&raquo;</a>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="col-12 text-center">
        <p>Aucune annonce trouvée.</p>
    </div>
<?php endif; ?>

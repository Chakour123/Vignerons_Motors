<?php
include("config/auth.php");
include("config/connect.php");
include("include/header.php");
include("include/menu.php");

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

// Fetch all categories into an array for multiple uses
$all_categories = [];
$cats_query = "SELECT * FROM categories ORDER BY nom ASC";
$cats_result = mysqli_query($id, $cats_query);
if ($cats_result) {
    while($cat = mysqli_fetch_assoc($cats_result)) {
        $all_categories[] = $cat;
    }
}
?>


<div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">ANNONCES</h1>
    </div>
    <div class="container-fluid product py-5">
        <div class="container py-5">


        <div class="row g-4">
                <div class="col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-categories mb-4">
                        <h1>Annonces</h1>
                    </div>
                </div>
                <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="row g-4">
                        <div class="col-xl-5">
                            <div class="input-group w-100 mx-auto d-flex">
                                <input type="search" id="search-input" class="form-control p-3" placeholder="Rechercher une annonce..."
                                    value="<?php echo htmlspecialchars($search_query); ?>" aria-describedby="search-icon-1">
                                <span id="search-icon-1" class="input-group-text p-3"><i
                                        class="fa fa-search"></i></span>
                            </div>
                        </div>
                        <div class="col-xl-5 text-end">
                            <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between">
                                <label for="category-select">Catégories:</label>
                                <select id="category-select" name="category"
                                    class="border-0 form-select-sm bg-light me-3">
                                    <option value="">Toutes les catégories</option>
                                    <?php foreach ($all_categories as $cat): ?>
                                        <option value="<?php echo $cat['idc']; ?>" <?php echo $category_filter == $cat['idc'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div><br><br>


            <div class="tab-class">
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4" id="ads-container">
                            <?php if ($ads_result && mysqli_num_rows($ads_result) > 0): ?>
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
                                            <a href="annonce.php?page=<?php echo $page - 1; ?><?php echo $category_filter ? '&category='.$category_filter : ''; ?><?php echo $search_query ? '&search='.$search_query : ''; ?>" class="rounded">&laquo;</a>
                                        <?php endif; ?>
                                        
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <a href="annonce.php?page=<?php echo $i; ?><?php echo $category_filter ? '&category='.$category_filter : ''; ?><?php echo $search_query ? '&search='.$search_query : ''; ?>" 
                                               class="rounded <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                                        <?php endfor; ?>
                                        
                                        <?php if ($page < $total_pages): ?>
                                            <a href="annonce.php?page=<?php echo $page + 1; ?><?php echo $category_filter ? '&category='.$category_filter : ''; ?><?php echo $search_query ? '&search='.$search_query : ''; ?>" class="rounded">&raquo;</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-12 text-center">
                                    <p>Aucune annonce trouvée.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const adsContainer = document.getElementById('ads-container');
    const categoryLinks = document.querySelectorAll('.product-categories a');
    
    let currentCategory = '<?php echo $category_filter ?: ""; ?>';
    let currentPage = 1;

    function fetchAds() {
        const searchQuery = searchInput.value;
        const url = `fetch_annonces.php?search=${encodeURIComponent(searchQuery)}&category=${currentCategory}&page=${currentPage}`;
        
        // Show loading state
        adsContainer.style.opacity = '0.5';

        fetch(url)
            .then(response => response.text())
            .then(html => {
                adsContainer.innerHTML = html;
                adsContainer.style.opacity = '1';
                // Re-bind pagination links since they were replaced
                bindPagination();
            });
    }

    function bindPagination() {
        const pgLinks = document.querySelectorAll('.pg-link');
        pgLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = this.getAttribute('data-page');
                fetchAds();
            });
        });
    }

    const categorySelect = document.getElementById('category-select');

    function updateActiveCategory(id) {
        currentCategory = id;
        currentPage = 1;
        
        // Sync Sidebar UI
        categoryLinks.forEach(l => {
            const urlParams = new URLSearchParams(l.getAttribute('href').split('?')[1]);
            const catId = urlParams.get('category') || '';
            if (catId === id) {
                l.classList.add('text-primary', 'fw-bold');
            } else {
                l.classList.remove('text-primary', 'fw-bold');
            }
        });
        
        // Sync Dropdown UI
        categorySelect.value = id;
        
        fetchAds();
    }

    categorySelect.addEventListener('change', function() {
        updateActiveCategory(this.value);
    });

    searchInput.addEventListener('input', function() {
        currentPage = 1; // Reset to page 1 on search
        fetchAds();
    });

    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const urlParams = new URLSearchParams(this.getAttribute('href').split('?')[1]);
            const id = urlParams.get('category') || '';
            updateActiveCategory(id);
        });
    });

    // Initial binding for SSR pagination
    const initialPgLinks = document.querySelectorAll('.pagination a');
    initialPgLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const urlParams = new URLSearchParams(this.getAttribute('href').split('?')[1]);
            if (urlParams.has('page')) {
                e.preventDefault();
                currentPage = urlParams.get('page');
                fetchAds();
            }
        });
    });
});
</script>

<?php
include("include/modal_details.php");
include("include/footer.php");
?>

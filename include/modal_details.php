<!-- Modal Annonce Details -->
<div class="modal fade" id="adDetailsModal" tabindex="-1" aria-labelledby="adDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="adDetailsModalLabel">Détails de l'annonce</h5>
                <div class="ms-auto d-flex align-items-center">
                    <button type="button" class="btn btn-link link-secondary p-0 me-3" id="btn-toggle-favorite" onclick="toggleFavorite()">
                        <i class="fa fa-heart fa-2x" id="modal-heart-icon"></i>
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <img src="" id="modal-ad-image" class="img-fluid rounded w-100" alt="Image de l'annonce">
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary" id="modal-ad-category">Catégorie</span>
                            <span class="text-primary fs-4 fw-bold" id="modal-ad-price">0.00 €</span>
                        </div>
                        <h2 class="h3 mb-3" id="modal-ad-title">Titre de l'annonce</h2>
                        <hr>
                        <p class="text-dark fw-bold mb-1">Description:</p>
                        <p id="modal-ad-description" class="text-muted mb-4">Description de l'annonce...</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-muted">
                                <i class="fa fa-eye me-1"></i> <span id="modal-ad-views">0</span> vues
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 rounded-pill py-3" id="btn-contact-seller">
                            <i class="fa fa-paper-plane me-2"></i> Envoyer un message
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentAdId = null;

function showAdDetails(id) {
    currentAdId = id;
    const modal = new bootstrap.Modal(document.getElementById('adDetailsModal'));
    const heartIcon = document.getElementById('modal-heart-icon');
    
    // Clear previous data
    document.getElementById('modal-ad-title').innerText = 'Chargement...';
    document.getElementById('modal-ad-description').innerText = '';
    document.getElementById('modal-ad-price').innerText = '';
    document.getElementById('modal-ad-category').innerText = '';
    document.getElementById('modal-ad-image').src = '';
    document.getElementById('modal-ad-views').innerText = '0';
    heartIcon.style.color = ''; // Reset color
    heartIcon.classList.remove('text-success');

    fetch(`get_annonce_details.php?id=${id}`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const ad = res.data;
                document.getElementById('modal-ad-title').innerText = ad.titre;
                document.getElementById('modal-ad-description').innerText = ad.description;
                document.getElementById('modal-ad-price').innerText = ad.prix_formatted;
                document.getElementById('modal-ad-category').innerText = ad.category_name;
                document.getElementById('modal-ad-image').src = `uploads/${ad.image}`;
                document.getElementById('modal-ad-views').innerText = ad.vues;
                
                if (res.is_favorite) {
                    heartIcon.classList.add('text-success');
                } else {
                    heartIcon.classList.remove('text-success');
                }
                
                // Set contact seller button
                const contactBtn = document.getElementById('btn-contact-seller');
                contactBtn.onclick = function() {
                    window.location.href = `chat.php?annonce_id=${ad.ida}&seller_id=${ad.user_id}`;
                };
                
                modal.show();
            } else {
                alert(res.message);
            }
        })
        .catch(err => {
            console.error('Error fetching ad details:', err);
            alert('Erreur lors du chargement des détails.');
        });
}

function toggleFavorite() {
    if (!currentAdId) return;
    
    const heartIcon = document.getElementById('modal-heart-icon');
    
    fetch(`toggle_favori.php?id=${currentAdId}`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                if (res.action === 'added') {
                    heartIcon.classList.add('text-success');
                } else {
                    heartIcon.classList.remove('text-success');
                }
            } else {
                alert(res.message);
                if (res.message.includes('connecter')) {
                    window.location.href = 'connexion.php';
                }
            }
        })
        .catch(err => {
            console.error('Error toggling favorite:', err);
        });
}
</script>

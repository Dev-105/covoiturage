<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/trip_functions.php';

$id = $_GET['id'] ?? null;
$trip = $id ? get_trip_by_id($id) : null;

// Si pas de trajet spécifique, afficher TOUS les trajets + recherche
if (!$trip) {
    // Récupérer tous les trajets actifs
    $all_trips = search_trips([]); // Pas de filtres = tous les trajets
    
    // Appliquer les filtres si recherche
    $filters = [
        'departure_city' => $_GET['departure_city'] ?? '',
        'arrival_city' => $_GET['arrival_city'] ?? '',
        'departure_date' => $_GET['departure_date'] ?? '',
        'min_seats' => $_GET['min_seats'] ?? ''
    ];
    
    $trips = !empty(array_filter($filters)) ? search_trips($filters) : $all_trips;
    ?>
    <section class="search-reserve-section">
        <div class="container">
            <div class="search-reserve-header">
                <h1>Tous les trajets disponibles</h1>
                <p>Choisissez parmi tous les trajets proposés par nos conducteurs</p>
            </div>

            <!-- Search Form -->
            <div class="search-reserve-form">
                <form method="get" action="reserver.php" class="search-form">
                    <div class="form-grid">
                        <div class="input-group">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" name="departure_city" placeholder="Ville de départ" 
                                   value="<?=htmlspecialchars($filters['departure_city'])?>">
                        </div>
                        
                        <div class="input-group">
                            <i class="fas fa-flag-checkered"></i>
                            <input type="text" name="arrival_city" placeholder="Ville d'arrivée" 
                                   value="<?=htmlspecialchars($filters['arrival_city'])?>">
                        </div>
                        
                        <div class="input-group">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" name="departure_date" 
                                   value="<?=htmlspecialchars($filters['departure_date'])?>">
                        </div>
                        
                        <div class="input-group">
                            <i class="fas fa-user-friends"></i>
                            <input type="number" name="min_seats" placeholder="Places min" min="1" 
                                   value="<?=htmlspecialchars($filters['min_seats'])?>">
                        </div>
                    </div>
                    <div class="search-actions">
                        <button type="submit" class="btn btn-primary btn-search">
                            <i class="fas fa-search"></i>
                            Filtrer les trajets
                        </button>
                        <?php if (!empty(array_filter($filters))): ?>
                            <a href="reserver.php" class="btn btn-outline">
                                <i class="fas fa-times"></i>
                                Voir tous
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Trip Counter -->
            <div class="trip-counter">
                <i class="fas fa-route"></i>
                <span><?=count($trips)?> trajet(s) disponible(s)</span>
                <?php if (!empty(array_filter($filters))): ?>
                    <span class="filter-info">(résultats filtrés)</span>
                <?php endif; ?>
            </div>

            <!-- All Trips List -->
            <div class="search-results">
                <?php if ($trips): ?>
                    <div class="trips-grid">
                        <?php foreach ($trips as $t): ?>
                            <div class="trip-card-search">
                                <div class="trip-header">
                                    <div class="route">
                                        <span class="city departure">
                                            <i class="fas fa-play-circle"></i>
                                            <?=htmlspecialchars($t['departure_city'])?>
                                        </span>
                                        <div class="route-line">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                        <span class="city arrival">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?=htmlspecialchars($t['arrival_city'])?>
                                        </span>
                                    </div>
                                    <div class="price"><?=htmlspecialchars($t['price'])?>€</div>
                                </div>
                                
                                <div class="trip-details">
                                    <div class="detail">
                                        <i class="fas fa-calendar"></i>
                                        <span><?=htmlspecialchars($t['departure_date'])?></span>
                                    </div>
                                    <div class="detail">
                                        <i class="fas fa-clock"></i>
                                        <span><?=htmlspecialchars($t['departure_time'])?></span>
                                    </div>
                                    <div class="detail">
                                        <i class="fas fa-user-friends"></i>
                                        <span><?=htmlspecialchars($t['available_seats'])?> places</span>
                                    </div>
                                </div>

                                <?php if (!empty($t['description'])): ?>
                                <div class="trip-description">
                                    <p><?=nl2br(htmlspecialchars(mb_strimwidth($t['description'], 0, 100, '...')))?></p>
                                </div>
                                <?php endif; ?>
                                
                                <div class="trip-driver">
                                    <i class="fas fa-user"></i>
                                    <span>Conducteur: <?=htmlspecialchars($t['first_name'] . ' ' . $t['last_name'])?></span>
                                </div>
                                
                                <div class="trip-actions">
                                    <?php if ($t['available_seats'] > 0): ?>
                                        <a href="reserver.php?id=<?=$t['id']?>" class="btn btn-primary">
                                            <i class="fas fa-ticket-alt"></i>
                                            Réserver
                                        </a>
                                    <?php else: ?>
                                        <span class="btn btn-disabled">
                                            <i class="fas fa-times-circle"></i>
                                            Complet
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h4>Aucun trajet trouvé</h4>
                        <p>
                        <?php 
                        if (!empty(array_filter($filters))) {
                            echo 'Aucun trajet ne correspond à vos critères. Essayez de modifier votre recherche.';
                        } else {
                            echo 'Aucun trajet disponible pour le moment. Revenez plus tard !';
                        }
                        ?>
                        </p>
                        <?php if (!empty(array_filter($filters))): ?>
                            <a href="reserver.php" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                Voir tous les trajets
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>

<!-- Page de réservation spécifique -->
<section class="reserve-section">
    <div class="container">
        <!-- Header pour passager -->
        <div class="passenger-header">
            <div class="passenger-info">
                <h1>Réserver un trajet</h1>
                <p>Vous êtes sur le point de réserver un trajet en tant que passager</p>
            </div>
            <a href="reserver.php" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Retour aux trajets
            </a>
        </div>

        <div class="reserve-container">
            <!-- Trip Details -->
            <div class="trip-details-card">
                <div class="trip-header">
                    <div class="route">
                        <span class="city departure">
                            <i class="fas fa-play-circle"></i>
                            <?=htmlspecialchars($trip['departure_city'])?>
                        </span>
                        <div class="route-line">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <span class="city arrival">
                            <i class="fas fa-map-marker-alt"></i>
                            <?=htmlspecialchars($trip['arrival_city'])?>
                        </span>
                    </div>
                    <div class="price"><?=htmlspecialchars($trip['price'])?>€</div>
                </div>
                
                <div class="trip-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <div class="info-content">
                                <span class="info-label">Date</span>
                                <span class="info-value"><?=htmlspecialchars($trip['departure_date'])?></span>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <div class="info-content">
                                <span class="info-label">Heure</span>
                                <span class="info-value"><?=htmlspecialchars($trip['departure_time'])?></span>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-user-friends"></i>
                            <div class="info-content">
                                <span class="info-label">Places disponibles</span>
                                <span class="info-value"><?=htmlspecialchars($trip['available_seats'])?></span>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <div class="info-content">
                                <span class="info-label">Conducteur</span>
                                <span class="info-value"><?=htmlspecialchars($trip['first_name'] . ' ' . $trip['last_name'])?></span>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($trip['description'])): ?>
                    <div class="trip-description">
                        <h4>Description du trajet</h4>
                        <p><?=nl2br(htmlspecialchars($trip['description']))?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reservation Form -->
            <div class="reservation-form-card">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="login-required">
                        <i class="fas fa-user-lock"></i>
                        <h3>Connexion requise</h3>
                        <p>Vous devez vous connecter pour réserver ce trajet</p>
                        <div class="auth-buttons">
                            <a href="connexion.php" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i>
                                Se connecter
                            </a>
                            <a href="inscription.php" class="btn btn-outline">
                                <i class="fas fa-user-plus"></i>
                                Créer un compte
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="reservation-form">
                        <h3>Confirmer la réservation</h3>
                        
                        <form method="post" action="reserver_trajet.php">
                            <input type="hidden" name="trip_id" value="<?=htmlspecialchars($trip['id'])?>">
                            
                            <div class="form-group">
                                <label for="seats">
                                    <i class="fas fa-ticket-alt"></i>
                                    Nombre de places
                                </label>
                                <input 
                                    type="number" 
                                    id="seats" 
                                    name="seats" 
                                    min="1" 
                                    max="<?=htmlspecialchars($trip['available_seats'])?>" 
                                    value="1" 
                                    required
                                >
                                <small class="form-help">
                                    Maximum <?=htmlspecialchars($trip['available_seats'])?> place(s) disponible(s)
                                </small>
                            </div>

                            <div class="price-summary">
                                <div class="price-item">
                                    <span>Prix par place</span>
                                    <span><?=htmlspecialchars($trip['price'])?>€</span>
                                </div>
                                <div class="price-item total">
                                    <span>Total estimé</span>
                                    <span id="total-price"><?=htmlspecialchars($trip['price'])?>€</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-full">
                                <i class="fas fa-check-circle"></i>
                                Confirmer la réservation
                            </button>
                        </form>

                        <div class="reservation-info">
                            <h4>Informations importantes</h4>
                            <ul>
                                <li>Le paiement se fait directement avec le conducteur</li>
                                <li>Annulation possible jusqu'à 24h avant le départ</li>
                                <li>Vous recevrez les coordonnées du conducteur après réservation</li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const seatsInput = document.getElementById('seats');
    const totalPrice = document.getElementById('total-price');
    const pricePerSeat = <?=htmlspecialchars($trip['price'])?>;

    function updateTotalPrice() {
        const seats = parseInt(seatsInput.value) || 1;
        const total = (seats * pricePerSeat).toFixed(2);
        totalPrice.textContent = total + '€';
    }

    if (seatsInput) {
        seatsInput.addEventListener('input', updateTotalPrice);
        updateTotalPrice();
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
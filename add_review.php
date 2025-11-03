<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/trip_functions.php';
require_login();

$trip_id = (int)($_GET['trip_id'] ?? 0);
$trip = get_trip_by_id($trip_id);
$user = current_user();

// Verify user can review
if (!$trip || !can_review_trip($trip_id, $user['id'])) {
    header('Location: my-trajet.php');
    exit;
}

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $error = 'La note doit être entre 1 et 5 étoiles';
    } elseif (empty($comment)) {
        $error = 'Le commentaire est requis';
    } else {
        $result = add_review([
            'trip_id' => $trip_id,
            'reviewer_id' => $user['id'],
            'driver_id' => $trip['driver_id'],
            'rating' => $rating,
            'comment' => $comment
        ]);

        if ($result === true) {
            header('Location: my-trajet.php?reviewed=1');
            exit;
        } else {
            $error = $result;
        }
    }
}
?>

<section class="review-section">
    <div class="container">
        <div class="review-header">
            <div class="header-content">
                <h1>Évaluer le trajet</h1>
                <p>Partagez votre expérience pour aider la communauté</p>
            </div>
            <a href="my-trajet.php" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Retour aux trajets
            </a>
        </div>

        <div class="review-container">
            <!-- Trip Summary -->
            <div class="trip-summary-card">
                <div class="card-header">
                    <i class="fas fa-route"></i>
                    <h3>Détails du trajet</h3>
                </div>
                <div class="trip-details">
                    <div class="route-display">
                        <div class="city departure">
                            <i class="fas fa-play-circle"></i>
                            <span class="city-name"><?=htmlspecialchars($trip['departure_city'])?></span>
                        </div>
                        <div class="route-line">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="city arrival">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="city-name"><?=htmlspecialchars($trip['arrival_city'])?></span>
                        </div>
                    </div>
                    
                    <div class="trip-info-grid">
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
                            <i class="fas fa-user"></i>
                            <div class="info-content">
                                <span class="info-label">Conducteur</span>
                                <span class="info-value"><?=htmlspecialchars($trip['first_name'] . ' ' . $trip['last_name'])?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <div class="review-form-card">
                <div class="card-header">
                    <i class="fas fa-star"></i>
                    <h3>Votre évaluation</h3>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?=htmlspecialchars($error)?>
                    </div>
                <?php endif; ?>

                <form method="post" class="review-form">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-star-half-alt"></i>
                            Note globale
                        </label>
                        <div class="rating-container">
                            <div class="rating-stars">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <input type="radio" id="star<?=$i?>" name="rating" value="<?=$i?>" required>
                                    <label for="star<?=$i?>" class="star-label">
                                        <i class="far fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <span class="star-text"><?=$i?> étoile<?=$i > 1 ? 's' : ''?></span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                            <div class="rating-labels">
                                <span>Pas bien</span>
                                <span>Excellent</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comment" class="form-label">
                            <i class="fas fa-comment"></i>
                            Votre commentaire
                        </label>
                        <textarea 
                            id="comment" 
                            name="comment" 
                            required 
                            placeholder="Décrivez votre expérience : ponctualité, conduite, ambiance, etc."
                            rows="6"
                            maxlength="500"
                        ><?=htmlspecialchars($_POST['comment'] ?? '')?></textarea>
                        <div class="char-counter">
                            <span id="char-count">0</span>/500 caractères
                        </div>
                    </div>

                    <div class="form-tips">
                        <h4>Conseils pour un bon commentaire :</h4>
                        <ul>
                            <li>Soyez honnête et constructif</li>
                            <li>Mentionnez la ponctualité et la conduite</li>
                            <li>Parlez de l'ambiance pendant le trajet</li>
                            <li>Évitez les commentaires personnels blessants</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-paper-plane"></i>
                        Publier mon évaluation
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for comment
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('char-count');
    
    if (commentTextarea && charCount) {
        commentTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        
        // Initialize counter
        charCount.textContent = commentTextarea.value.length;
    }
    
    // Star rating interaction
    const starInputs = document.querySelectorAll('.rating-stars input[type="radio"]');
    starInputs.forEach(input => {
        input.addEventListener('change', function() {
            const rating = parseInt(this.value);
            highlightStars(rating);
        });
    });
    
    function highlightStars(rating) {
        const stars = document.querySelectorAll('.star-label');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
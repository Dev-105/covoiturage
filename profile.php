<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/auth_functions.php';
require_once __DIR__ . '/includes/trip_functions.php';
require_login();
$user = current_user();

$success = null; $errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if ($first === '' || $last === '') $errors[] = 'Prénom et nom requis';
    if (empty($errors)) {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE users SET first_name = :f, last_name = :l, phone = :p WHERE id = :id');
        $stmt->execute([':f'=>$first,':l'=>$last,':p'=>$phone,':id'=>$user['id']]);
        $success = 'Profil mis à jour avec succès';
        $user = current_user();
    }
}
?>

<section class="profile-section">
    <div class="container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="profile-info">
                <h1><?=htmlspecialchars($user['first_name'] . ' ' . $user['last_name'])?></h1>
                <p class="profile-email"><?=htmlspecialchars($user['email'])?></p>
                <div class="profile-stats">
                    <div class="stat">
                        <span class="stat-number"><?=get_user_trips_count($user['id'])?></span>
                        <span class="stat-label">Trajets proposés</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number"><?=get_user_reservations_count($user['id'])?></span>
                        <span class="stat-label">Réservations</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-content">
            <!-- Edit Form -->
            <div class="profile-form-container">
                <div class="form-header">
                    <h2>Informations personnelles</h2>
                    <p>Modifiez vos informations de profil</p>
                </div>

                <!-- Messages -->
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?=htmlspecialchars($success)?>
                    </div>
                <?php endif; ?>
                
                <?php if ($errors): ?>
                    <?php foreach ($errors as $e): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?=htmlspecialchars($e)?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Profile Form -->
                <form method="post" class="profile-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">
                                <i class="fas fa-user"></i>
                                Prénom
                            </label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                value="<?=htmlspecialchars($user['first_name'])?>" 
                                required
                                placeholder="Votre prénom"
                            >
                        </div>

                        <div class="form-group">
                            <label for="last_name">
                                <i class="fas fa-user"></i>
                                Nom
                            </label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                value="<?=htmlspecialchars($user['last_name'])?>" 
                                required
                                placeholder="Votre nom"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i>
                            Téléphone
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?=htmlspecialchars($user['phone'])?>"
                            placeholder="Votre numéro de téléphone"
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <div class="readonly-field">
                            <?=htmlspecialchars($user['email'])?>
                            <span class="field-note">(Non modifiable)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-user-tag"></i>
                            Rôle
                        </label>
                        <div class="readonly-field">
                            <?=htmlspecialchars(ucfirst($user['role']))?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-save"></i>
                        Enregistrer les modifications
                    </button>
                </form>
            </div>

            <!-- Account Info -->
            <div class="account-info">
                <div class="info-card">
                    <h3>Statistiques du compte</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="info-content">
                                <span class="info-label">Membre depuis</span>
                                <span class="info-value">
                                    <?=htmlspecialchars(date('d/m/Y', strtotime($user['created_at'])))?>
                                </span>
                            </div>
                        </div>
                        <?php if ($user['role'] === 'conducteur'): ?>
                        <div class="info-item">
                            <i class="fas fa-star"></i>
                            <div class="info-content">
                                <span class="info-label">Note moyenne</span>
                                <span class="info-value">
                                    <?php if ($user['average_rating'] !== null): ?>
                                        <span class="rating-stars">
                                            <?php
                                            $rating = round($user['average_rating']);
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating) {
                                                    echo '<i class="fas fa-star"></i>';
                                                } else {
                                                    echo '<i class="far fa-star"></i>';
                                                }
                                            }
                                            ?>
                                        </span>
                                        <?=number_format($user['average_rating'], 1)?>/5 
                                        <span class="reviews-count">(<?=$user['total_reviews']?> avis)</span>
                                    <?php else: ?>
                                        <span class="no-reviews">Aucun avis reçu</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <i class="fas fa-car"></i>
                            <div class="info-content">
                                <span class="info-label">Trajets proposés</span>
                                <span class="info-value"><?=get_user_trips_count($user['id'])?></span>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-ticket-alt"></i>
                            <div class="info-content">
                                <span class="info-label">Réservations</span>
                                <span class="info-value"><?=get_user_reservations_count($user['id'])?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($user['role'] === 'conducteur'): ?>
                <div class="info-card">
                    <h3>Avis reçus</h3>
                    <div class="reviews-list">
                        <?php
                        $reviews = get_user_reviews($user['id']);
                        if ($reviews): 
                            foreach ($reviews as $review):
                        ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa<?= $i <= $review['rating'] ? 's' : 'r' ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="review-date">
                                        <?=htmlspecialchars(date('d/m/Y', strtotime($review['created_at'])))?>
                                    </div>
                                </div>
                                <div class="review-content">
                                    <p class="review-text"><?=nl2br(htmlspecialchars($review['comment']))?></p>
                                    <p class="review-trip">
                                        <i class="fas fa-route"></i>
                                        <?=htmlspecialchars($review['departure_city'])?> → 
                                        <?=htmlspecialchars($review['arrival_city'])?>
                                    </p>
                                    <p class="review-author">
                                        <i class="fas fa-user"></i>
                                        <?=htmlspecialchars($review['reviewer_first_name'])?> 
                                    </p>
                                </div>
                            </div>
                        <?php 
                            endforeach;
                        else:
                        ?>
                            <p class="no-reviews-message">Aucun avis reçu pour le moment</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="info-card">
                    <h3>Actions du compte</h3>
                    <div class="action-list">
                        <a href="my-trajet.php" class="action-link">
                            <i class="fas fa-route"></i>
                            <span>Voir mes trajets</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="propose.php" class="action-link">
                            <i class="fas fa-plus-circle"></i>
                            <span>Proposer un trajet</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="logout.php" class="action-link logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Se déconnecter</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
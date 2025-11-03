<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/trip_functions.php';
require_login();
$user = current_user();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'driver_id' => $user['id'],
        'departure_city' => trim($_POST['departure_city'] ?? ''),
        'arrival_city' => trim($_POST['arrival_city'] ?? ''),
        'departure_date' => $_POST['departure_date'] ?? '',
        'departure_time' => $_POST['departure_time'] ?? '',
        'available_seats' => (int)($_POST['available_seats'] ?? 0),
        'price' => (float)($_POST['price'] ?? 0),
        'description' => trim($_POST['description'] ?? '')
    ];
    if ($data['available_seats'] <= 0) $errors[] = 'Précisez au moins 1 place';
    if (!$data['departure_date'] || !$data['departure_time']) $errors[] = 'Date et heure requises';

    if (empty($errors)) {
        try {
            create_trip($data);
            header('Location: my-trajet.php');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Erreur lors de la création du trajet: ' . $e->getMessage();
        }
    }
}
?>

<section class="propose-section">
    <div class="container">
        <div class="propose-header">
            <h1>Proposer un trajet</h1>
            <p>Partagez votre trajet et voyagez à plusieurs</p>
        </div>

        <div class="propose-container">
            <!-- Form Section -->
            <div class="propose-form-container">
                <?php if ($errors): ?>
                    <?php foreach ($errors as $err): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?=htmlspecialchars($err)?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <form method="post" class="propose-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="departure_city">
                                <i class="fas fa-map-marker-alt"></i>
                                Ville de départ
                            </label>
                            <input 
                                type="text" 
                                id="departure_city" 
                                name="departure_city" 
                                placeholder="Ex: Paris" 
                                required
                                value="<?=htmlspecialchars($_POST['departure_city'] ?? '')?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="arrival_city">
                                <i class="fas fa-flag-checkered"></i>
                                Ville d'arrivée
                            </label>
                            <input 
                                type="text" 
                                id="arrival_city" 
                                name="arrival_city" 
                                placeholder="Ex: Lyon" 
                                required
                                value="<?=htmlspecialchars($_POST['arrival_city'] ?? '')?>"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="departure_date">
                                <i class="fas fa-calendar-alt"></i>
                                Date de départ
                            </label>
                            <input 
                                type="date" 
                                id="departure_date" 
                                name="departure_date" 
                                required
                                value="<?=htmlspecialchars($_POST['departure_date'] ?? '')?>"
                                min="<?=date('Y-m-d')?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="departure_time">
                                <i class="fas fa-clock"></i>
                                Heure de départ
                            </label>
                            <input 
                                type="time" 
                                id="departure_time" 
                                name="departure_time" 
                                required
                                value="<?=htmlspecialchars($_POST['departure_time'] ?? '')?>"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="available_seats">
                                <i class="fas fa-user-friends"></i>
                                Places disponibles
                            </label>
                            <input 
                                type="number" 
                                id="available_seats" 
                                name="available_seats" 
                                min="1" 
                                max="8"
                                placeholder="1 à 8 places"
                                required
                                value="<?=htmlspecialchars($_POST['available_seats'] ?? '')?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="price">
                                <i class="fas fa-euro-sign"></i>
                                Prix par place
                            </label>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                step="0.01" 
                                min="0"
                                placeholder="0.00"
                                required
                                value="<?=htmlspecialchars($_POST['price'] ?? '')?>"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-comment-alt"></i>
                            Description (optionnelle)
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            placeholder="Décrivez votre trajet, points de rendez-vous, préférences..."
                            rows="4"
                        ><?=htmlspecialchars($_POST['description'] ?? '')?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-paper-plane"></i>
                        Publier le trajet
                    </button>
                </form>
            </div>

            <!-- Tips Section -->
            <div class="propose-tips">
                <div class="tips-card">
                    <h3>Conseils pour un bon trajet</h3>
                    
                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <div class="tip-content">
                            <strong>Soignez votre description</strong>
                            <p>Précisez les points de rendez-vous et vos préférences</p>
                        </div>
                    </div>

                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <div class="tip-content">
                            <strong>Prix juste</strong>
                            <p>Proposez un prix équitable qui couvre vos frais</p>
                        </div>
                    </div>

                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <div class="tip-content">
                            <strong>Horaires réalistes</strong>
                            <p>Prévoyez du temps pour les imprévus</p>
                        </div>
                    </div>

                    <div class="tip-item">
                        <i class="fas fa-check-circle"></i>
                        <div class="tip-content">
                            <strong>Communication</strong>
                            <p>Restez disponible pour répondre aux passagers</p>
                        </div>
                    </div>

                    <div class="stats-preview">
                        <h4>Vos statistiques</h4>
                        <div class="stats-grid">
                            <div class="stat-preview">
                                <span class="stat-number"><?=get_user_trips_count($user['id'])?></span>
                                <span class="stat-label">Trajets proposés</span>
                            </div>
                            <div class="stat-preview">
                                <span class="stat-number"><?=get_user_reservations_count($user['id'])?></span>
                                <span class="stat-label">Réservations reçues</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
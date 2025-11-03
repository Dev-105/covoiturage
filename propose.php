<?php
require_once __DIR__ . '/includes/auth_functions.php';
require_once __DIR__ . '/includes/trip_functions.php';
require_login();
$user = current_user();

// Rediriger les passagers vers la page d'accueil
if ($user['role'] !== 'conducteur') {
    header('Location: index.php');
    exit;
}

$errors = [];
$edit_mode = false;
$trip_to_edit = null;

// Récupérer le trajet à éditer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $trip_to_edit = get_trip_by_id($_GET['id']);
    if ($trip_to_edit && $trip_to_edit['driver_id'] == $user['id']) {
        $edit_mode = true;
    } else {
        $trip_to_edit = null;
    }
}

// Traitement du formulaire
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
            if ($edit_mode && $trip_to_edit) {
                // Update existing trip
                $pdo = getPDO();
                $stmt = $pdo->prepare("UPDATE trips SET departure_city = :departure_city, arrival_city = :arrival_city, departure_date = :departure_date, departure_time = :departure_time, available_seats = :available_seats, price = :price, description = :description WHERE id = :id AND driver_id = :driver_id");
                $stmt->execute([
                    ':departure_city' => $data['departure_city'],
                    ':arrival_city' => $data['arrival_city'],
                    ':departure_date' => $data['departure_date'],
                    ':departure_time' => $data['departure_time'],
                    ':available_seats' => $data['available_seats'],
                    ':price' => $data['price'],
                    ':description' => $data['description'],
                    ':id' => $trip_to_edit['id'],
                    ':driver_id' => $user['id']
                ]);
            } else {
                create_trip($data);
            }
            header('Location: my-trajet.php');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Erreur lors de la ' . ($edit_mode ? 'modification' : 'création') . ' du trajet: ' . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';

$errors = [];
$edit_mode = false;
$trip_to_edit = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $trip_to_edit = get_trip_by_id($_GET['id']);
    if ($trip_to_edit && $trip_to_edit['driver_id'] == $user['id']) {
        $edit_mode = true;
    } else {
        $trip_to_edit = null;
    }
}

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
            if ($edit_mode && $trip_to_edit) {
                // Update existing trip
                $pdo = getPDO();
                $stmt = $pdo->prepare("UPDATE trips SET departure_city = :departure_city, arrival_city = :arrival_city, departure_date = :departure_date, departure_time = :departure_time, available_seats = :available_seats, price = :price, description = :description WHERE id = :id AND driver_id = :driver_id");
                $stmt->execute([
                    ':departure_city' => $data['departure_city'],
                    ':arrival_city' => $data['arrival_city'],
                    ':departure_date' => $data['departure_date'],
                    ':departure_time' => $data['departure_time'],
                    ':available_seats' => $data['available_seats'],
                    ':price' => $data['price'],
                    ':description' => $data['description'],
                    ':id' => $trip_to_edit['id'],
                    ':driver_id' => $user['id']
                ]);
            } else {
                create_trip($data);
            }
            header('Location: my-trajet.php');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Erreur lors de la ' . ($edit_mode ? 'modification' : 'création') . ' du trajet: ' . $e->getMessage();
        }
    }
}
?>

<section class="propose-section">
    <div class="container">
        <div class="propose-header">
            <h1><?= $edit_mode ? 'Modifier le trajet' : 'Proposer un trajet' ?></h1>
            <p><?= $edit_mode ? 'Modifiez les informations de votre trajet' : 'Partagez votre trajet et voyagez à plusieurs' ?></p>
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
                                value="<?=htmlspecialchars($_POST['departure_city'] ?? ($edit_mode && $trip_to_edit ? $trip_to_edit['departure_city'] : ''))?>"
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
                                value="<?=htmlspecialchars($_POST['arrival_city'] ?? ($edit_mode && $trip_to_edit ? $trip_to_edit['arrival_city'] : ''))?>"
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
                                value="<?=htmlspecialchars($_POST['departure_date'] ?? ($edit_mode && $trip_to_edit ? $trip_to_edit['departure_date'] : ''))?>"
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
                                value="<?=htmlspecialchars($_POST['departure_time'] ?? ($edit_mode && $trip_to_edit ? $trip_to_edit['departure_time'] : ''))?>"
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
                                value="<?=htmlspecialchars($_POST['available_seats'] ?? ($edit_mode && $trip_to_edit ? $trip_to_edit['available_seats'] : ''))?>"
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
                                value="<?=htmlspecialchars($_POST['price'] ?? ($edit_mode && $trip_to_edit ? $trip_to_edit['price'] : ''))?>"
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
                        ><?=htmlspecialchars($_POST['description'] ?? ($edit_mode && $trip_to_edit ? $trip_to_edit['description'] : ''))?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-paper-plane"></i>
                        <?= $edit_mode ? 'Enregistrer les modifications' : 'Publier le trajet' ?>
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
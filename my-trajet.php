<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/trip_functions.php';
require_login();
$user = current_user();

$mine = get_user_trips($user['id']);
$res = get_user_reservations($user['id']);
?>

<section class="my-trips-section">
    <div class="container">
        <div class="page-header">
            <h1>Mes trajets</h1>
            <p>Gérez vos trajets proposés et vos réservations</p>
        </div>

        <div class="trips-layout">
            <!-- Mes trajets proposés -->
            <div class="trips-column">
                <div class="section-header">
                    <div class="header-title">
                        <i class="fas fa-car"></i>
                        <h2>Mes trajets proposés</h2>
                    </div>
                    <a href="propose.php" class="btn btn-primary btn-small">
                        <i class="fas fa-plus"></i>
                        Proposer un trajet
                    </a>
                </div>

                <?php if ($mine): ?>
                    <div class="trips-list">
                        <?php foreach ($mine as $t): ?>
                            <div class="trip-card">
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

                                <div class="trip-status">
                                    <span class="status-badge status-<?=$t['available_seats'] > 0 ? 'active' : 'full'?>">
                                        <i class="fas fa-<?=$t['available_seats'] > 0 ? 'check' : 'times'?>-circle"></i>
                                        <?=$t['available_seats'] > 0 ? 'Disponible' : 'Complet'?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-car-side"></i>
                        <h3>Aucun trajet proposé</h3>
                        <p>Vous n'avez pas encore proposé de trajet</p>
                        <a href="propose.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Proposer mon premier trajet
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mes réservations -->
            <div class="trips-column">
                <div class="section-header">
                    <div class="header-title">
                        <i class="fas fa-ticket-alt"></i>
                        <h2>Mes réservations</h2>
                    </div>
                </div>

                <?php if ($res): ?>
                    <div class="reservations-list">
                        <?php foreach ($res as $r): ?>
                            <div class="reservation-card">
                                <div class="reservation-header">
                                    <div class="route">
                                        <span class="city"><?=htmlspecialchars($r['departure_city'])?></span>
                                        <i class="fas fa-arrow-right"></i>
                                        <span class="city"><?=htmlspecialchars($r['arrival_city'])?></span>
                                    </div>
                                    <div class="reservation-status status-<?=htmlspecialchars($r['status'])?>">
                                        <?=htmlspecialchars($r['status'])?>
                                    </div>
                                </div>
                                
                                <div class="reservation-details">
                                    <div class="detail">
                                        <i class="fas fa-calendar"></i>
                                        <span><?=htmlspecialchars($r['departure_date'])?> à <?=htmlspecialchars($r['departure_time'])?></span>
                                    </div>
                                    <div class="detail">
                                        <i class="fas fa-users"></i>
                                        <span><?=htmlspecialchars($r['seats_reserved'])?> place(s) réservée(s)</span>
                                    </div>
                                    <div class="detail">
                                        <i class="fas fa-user"></i>
                                        <span>Conducteur: <?=htmlspecialchars($r['driver_first_name'] . ' ' . $r['driver_last_name'])?></span>
                                    </div>
                                </div>

                                <div class="reservation-actions">
                                    <?php if ($r['status'] === 'confirmed'): ?>
                                        <span class="status-badge status-confirmed">
                                            <i class="fas fa-check-circle"></i>
                                            Confirmé
                                        </span>
                                    <?php elseif ($r['status'] === 'pending'): ?>
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock"></i>
                                            En attente
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-cancelled">
                                            <i class="fas fa-times-circle"></i>
                                            Annulé
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-ticket-alt"></i>
                        <h3>Aucune réservation</h3>
                        <p>Vous n'avez pas encore réservé de trajet</p>
                        <a href="index.php" class="btn btn-outline">
                            <i class="fas fa-search"></i>
                            Chercher un trajet
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
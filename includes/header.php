<?php
// includes/header.php
require_once __DIR__ . '/auth_functions.php';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Covoiturage Local</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/reviews.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <i class="fas fa-car-side logo-icon"></i>
                    <a href="index.php" class="logo-text">Covoit<span>Local</span></a>
                </div>

                <!-- Navigation -->
                <nav class="main-nav">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        Accueil
                    </a>
                    
                    <!-- Lien pour rechercher/réserver des trajets -->
                    <a href="reserver.php" class="nav-link">
                        <i class="fas fa-search"></i>
                        Trouver un trajet
                    </a>
                    
                    <?php if ($user): ?>
                        <?php if ($user['role'] === 'conducteur'): ?>
                            <a href="propose.php" class="nav-link">
                                <i class="fas fa-plus"></i>
                                Proposer
                            </a>
                        <?php endif; ?>
                        <a href="my-trajet.php" class="nav-link">
                            <i class="fas fa-route"></i>
                            Mes trajets
                        </a>
                        <a href="profile.php" class="nav-link">
                            <i class="fas fa-user"></i>
                            Profil
                        </a>
                        <a href="logout.php" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i>
                            Déconnexion
                        </a>
                    <?php else: ?>
                        <div class="auth-buttons">
                            <a href="connexion.php" class="btn btn-outline">
                                <i class="fas fa-sign-in-alt"></i>
                                Connexion
                            </a>
                            <a href="inscription.php" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i>
                                Inscription
                            </a>
                        </div>
                    <?php endif; ?>
                </nav>

                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <nav class="mobile-nav" id="mobileNav">
                <a href="index.php" class="mobile-nav-link">
                    <i class="fas fa-home"></i>
                    <span>Accueil</span>
                </a>
                
                <!-- Lien mobile pour rechercher/réserver -->
                <a href="reserver.php" class="mobile-nav-link">
                    <i class="fas fa-search"></i>
                    <span>Trouver un trajet</span>
                </a>
                
                <?php if ($user): ?>
                    <?php if ($user['role'] === 'conducteur'): ?>
                        <a href="propose.php" class="mobile-nav-link">
                            <i class="fas fa-plus"></i>
                            <span>Proposer un trajet</span>
                        </a>
                    <?php endif; ?>
                    <a href="my-trajet.php" class="mobile-nav-link">
                        <i class="fas fa-route"></i>
                        <span>Mes trajets</span>
                    </a>
                    <a href="profile.php" class="mobile-nav-link">
                        <i class="fas fa-user"></i>
                        <span>Mon profil</span>
                    </a>
                    <div class="mobile-user-info">
                        <i class="fas fa-user-circle"></i>
                        <span><?=htmlspecialchars($user['first_name'])?> <?=htmlspecialchars($user['last_name'])?></span>
                    </div>
                    <a href="logout.php" class="mobile-nav-link logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </a>
                <?php else: ?>
                    <a href="connexion.php" class="mobile-nav-link">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Connexion</span>
                    </a>
                    <a href="inscription.php" class="mobile-nav-link">
                        <i class="fas fa-user-plus"></i>
                        <span>Inscription</span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
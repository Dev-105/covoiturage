<?php
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-background">
        <img src="car.webp" alt="Covoiturage entre personnes" class="hero-image">
        <div class="hero-overlay"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                Covoiturage <span>Local</span>
            </h1>
            <p class="hero-subtitle">
                Voyagez malin, économisez plus, polluez moins. 
                Rejoignez notre communauté de conducteurs et passagers.
            </p>
            <div class="hero-buttons">
                <?php if ($user): ?>
                    <a href="propose.php" class="btn btn-primary btn-large">
                        <i class="fas fa-plus"></i>
                        Proposer un trajet
                    </a>
                    <a href="reserver.php" class="btn btn-outline btn-large">
                        <i class="fas fa-search"></i>
                        Chercher un trajet
                    </a>
                <?php else: ?>
                    <a href="inscription.php" class="btn btn-primary btn-large">
                        <i class="fas fa-user-plus"></i>
                        Commencer maintenant
                    </a>
                    <a href="connexion.php" class="btn btn-outline btn-large">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <i class="fas fa-users"></i>
                <div class="stat-content">
                    <span class="stat-number">10,000+</span>
                    <span class="stat-label">Membres</span>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-route"></i>
                <div class="stat-content">
                    <span class="stat-number">5,000+</span>
                    <span class="stat-label">Trajets</span>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-leaf"></i>
                <div class="stat-content">
                    <span class="stat-number">120+</span>
                    <span class="stat-label">Tonnes CO2 économisées</span>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-euro-sign"></i>
                <div class="stat-content">
                    <span class="stat-number">300K+</span>
                    <span class="stat-label">€ économisés</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How it Works Section -->
<section class="how-it-works">
    <div class="container">
        <h2 class="section-title">Comment <span>ça marche</span> ?</h2>
        <div class="steps">
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>1. Inscrivez-vous</h3>
                <p>Créez votre compte en 2 minutes gratuitement</p>
            </div>
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>2. Trouvez un trajet</h3>
                <p>Recherchez ou proposez un trajet selon vos besoins</p>
            </div>
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-road"></i>
                </div>
                <h3>3. Voyagez</h3>
                <p>Confirmez et partez l'esprit tranquille</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <h2 class="section-title">Pourquoi nous <span>choisir</span> ?</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-euro-sign feature-icon"></i>
                <h3>Économique</h3>
                <p>Partagez les frais de route et voyagez à moindre coût</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-leaf feature-icon"></i>
                <h3>Écologique</h3>
                <p>Réduisez votre empreinte carbone en partageant vos trajets</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-shield-alt feature-icon"></i>
                <h3>Sécurisé</h3>
                <p>Profils vérifiés et système de notation pour votre sécurité</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-bolt feature-icon"></i>
                <h3>Rapide</h3>
                <p>Trouvez un trajet en quelques clics seulement</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <div class="cta-content">
            <h2>Prêt à commencer ?</h2>
            <p>Rejoignez notre communauté dès aujourd'hui</p>
            <a href="<?= $user ? 'propose.php' : 'inscription.php' ?>" class="btn btn-primary btn-large">
                <i class="fas fa-rocket"></i>
                Commencer maintenant
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
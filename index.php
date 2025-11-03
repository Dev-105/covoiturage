<?php
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
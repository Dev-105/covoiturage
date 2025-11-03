<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/auth_functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'phone' => trim($_POST['phone'] ?? ''),
        'role' => $_POST['role'] ?? 'passager'
    ];
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide';
    if (strlen($data['password']) < 6) $errors[] = 'Mot de passe trop court (min 6 caractères)';

    if (empty($errors)) {
        try {
            register_user($data);
            header('Location: connexion.php?registered=1');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Erreur lors de l\'inscription : ' . $e->getMessage();
        }
    }
}
?>

<section class="auth-section">
    <div class="container">
        <div class="auth-container">
            <!-- Left Side - Form -->
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2>Inscription</h2>
                    <p>Rejoignez notre communauté CovoitLocal</p>
                </div>

                <!-- Errors -->
                <?php if ($errors): ?>
                    <?php foreach ($errors as $err): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?=htmlspecialchars($err)?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Registration Form -->
                <form method="post" class="auth-form">
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
                                placeholder="Votre prénom" 
                                required
                                value="<?=htmlspecialchars($data['first_name'] ?? '')?>"
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
                                placeholder="Votre nom" 
                                required
                                value="<?=htmlspecialchars($data['last_name'] ?? '')?>"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="votre@email.com" 
                            required
                            value="<?=htmlspecialchars($data['email'] ?? '')?>"
                        >
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
                            placeholder="Votre numéro de téléphone"
                            value="<?=htmlspecialchars($data['phone'] ?? '')?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="role">
                            <i class="fas fa-user-tag"></i>
                            Rôle
                        </label>
                        <select id="role" name="role" required>
                            <option value="passager" <?=($data['role'] ?? 'passager') === 'passager' ? 'selected' : ''?>>Passager</option>
                            <option value="conducteur" <?=($data['role'] ?? '') === 'conducteur' ? 'selected' : ''?>>Conducteur</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i>
                            Mot de passe
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Minimum 6 caractères" 
                            required
                        >
                        <small class="form-help">Minimum 6 caractères</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-user-plus"></i>
                        Créer mon compte
                    </button>
                </form>

                <!-- Links -->
                <div class="auth-links">
                    <p>Déjà un compte ? 
                        <a href="connexion.php" class="auth-link">
                            Se connecter
                        </a>
                    </p>
                </div>
            </div>

            <!-- Right Side - Illustration -->
            <div class="auth-illustration">
                <div class="illustration-content">
                    <!-- <i class="fas fa-users"></i> -->
                    <img src="car.png" alt="" style="width: 80px;height: 80px; border-radius: 20px;">
                    <h3>Rejoignez-nous !</h3>
                    <p>Plus de 10,000 membres nous font déjà confiance pour leurs trajets</p>
                    
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Voyagez moins cher</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Rencontrez des personnes</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Protégez l'environnement</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
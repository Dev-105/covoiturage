<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/auth_functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (login_user($email, $password)) {
        header('Location: index.php');
        exit;
    } else {
        $errors[] = 'Email ou mot de passe incorrect';
    }
}
?>

<section class="auth-section">
    <div class="container">
        <div class="auth-container">
            <!-- Left Side - Illustration -->
            <div class="auth-illustration">
                <div class="illustration-content">
                    <!-- <i class="fas fa-car-side"></i> -->
                     <img src="car.png" alt="" style="width: 80px;height: 80px; border-radius: 20px;">
                    <h3>Content de vous revoir !</h3>
                    <p>Reconnectez-vous à votre compte pour continuer votre voyage</p>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2>Connexion</h2>
                    <p>Accédez à votre compte CovoitLocal</p>
                </div>

                <!-- Messages -->
                <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Inscription réussie. Veuillez vous connecter.
                    </div>
                <?php endif; ?>
                
                <?php if ($errors): ?>
                    <?php foreach ($errors as $err): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?=htmlspecialchars($err)?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="post" class="auth-form">
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
                            value="<?=htmlspecialchars($_POST['email'] ?? '')?>"
                        >
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
                            placeholder="Votre mot de passe" 
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter
                    </button>
                </form>

                <!-- Links -->
                <div class="auth-links">
                    <p>Pas encore de compte ? 
                        <a href="inscription.php" class="auth-link">
                            Créer un compte
                        </a>
                    </p>
                    <a href="#" class="auth-link forgot-password">
                        <i class="fas fa-key"></i>
                        Mot de passe oublié ?
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
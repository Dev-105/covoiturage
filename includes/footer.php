</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <!-- Logo & Description -->
            <div class="footer-section">
                <div class="footer-logo">
                    <!-- <i class="fas fa-car-side"></i> -->
                    <img src="car.png" alt="" style="width: 50px;height: 50px; border-radius: 50%;">
                    <span>CovoitLocal</span>
                </div>
                <p class="footer-description">
                    La plateforme de covoiturage locale pour des trajets économiques et écologiques.
                </p>
            </div>

            <!-- Liens rapides -->
            <div class="footer-section">
                <h4>Navigation</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="propose.php">Proposer un trajet</a></li>
                    <li><a href="my-trajet.php">Mes trajets</a></li>
                    <li><a href="profile.php">Mon profil</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-section">
                <h4>Contact</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>contact@covoitlocal.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+33 1 23 45 67 89</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Paris, France</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <p>&copy; 2026 CovoitLocal. Tous droits réservés.</p>
        </div>
    </div>
</footer>
<script>
    // Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNav = document.getElementById('mobileNav');
    
    if (mobileMenuBtn && mobileNav) {
        mobileMenuBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            mobileNav.classList.toggle('active');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenuBtn.contains(event.target) && !mobileNav.contains(event.target)) {
                mobileMenuBtn.classList.remove('active');
                mobileNav.classList.remove('active');
            }
        });
    }
});
</script>
</body>
</html>
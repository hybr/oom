<footer class="footer bg-dark text-light mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5><i class="bi bi-shop"></i> V4L</h5>
                <p class="text-muted">Your Community, Your Marketplace</p>
                <p class="small text-muted">
                    Connecting local organizations with local customers.
                </p>
            </div>

            <div class="col-md-2 mb-3">
                <h6>Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= url() ?>" class="text-light text-decoration-none">Home</a></li>
                    <li><a href="<?= url('marketplace.php') ?>" class="text-light text-decoration-none">Marketplace</a></li>
                    <li><a href="<?= url('vacancies.php') ?>" class="text-light text-decoration-none">Jobs</a></li>
                </ul>
            </div>

            <div class="col-md-2 mb-3">
                <h6>About</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= url('about.php') ?>" class="text-light text-decoration-none">About Us</a></li>
                    <li><a href="<?= url('contact.php') ?>" class="text-light text-decoration-none">Contact</a></li>
                    <li><a href="<?= url('faq.php') ?>" class="text-light text-decoration-none">FAQ</a></li>
                </ul>
            </div>

            <div class="col-md-2 mb-3">
                <h6>Legal</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= url('terms.php') ?>" class="text-light text-decoration-none">Terms of Service</a></li>
                    <li><a href="<?= url('privacy.php') ?>" class="text-light text-decoration-none">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="col-md-2 mb-3">
                <h6>Follow Us</h6>
                <div class="social-links">
                    <a href="#" class="text-light me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-light me-2"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-light me-2"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-secondary">

        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0 small text-muted">
                    &copy; <?= date('Y') ?> V4L - Vocal 4 Local. All rights reserved.
                    <span class="mx-2">|</span>
                    Version <?= APP_VERSION ?>
                </p>
            </div>
        </div>
    </div>
</footer>

    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>V4L - Vocal 4 Local</h5>
                    <p class="text-muted">Your Community, Your Marketplace.</p>
                    <p class="text-muted small">Connecting local organizations with local customers.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo url('/'); ?>" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="<?php echo url('pages/market/catalog.php'); ?>" class="text-muted text-decoration-none">Marketplace</a></li>
                        <li><a href="<?php echo url('pages/market/jobs.php'); ?>" class="text-muted text-decoration-none">Jobs</a></li>
                        <?php if (!auth()->check()): ?>
                            <li><a href="<?php echo url('pages/auth/signup.php'); ?>" class="text-muted text-decoration-none">Sign Up</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6>About</h6>
                    <p class="text-muted small">
                        V4L is a platform designed to help local businesses and organizations connect with their community.
                        Discover local products, services, and job opportunities.
                    </p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 text-muted small">
                    &copy; <?php echo date('Y'); ?> V4L - Vocal 4 Local. All rights reserved.
                </div>
                <div class="col-md-6 text-end text-muted small">
                    Version 1.0.0 | Built with ❤️ for local communities
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="<?php echo url('assets/js/app.js'); ?>"></script>

    <?php clearOldInput(); // Clear old form input and errors ?>
</body>
</html>

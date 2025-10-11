    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="bi bi-shop"></i> V4L</h5>
                    <p class="text-muted">Your Community, Your Marketplace</p>
                </div>
                <div class="col-md-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-white-50">About</a></li>
                        <li><a href="/contact" class="text-white-50">Contact</a></li>
                        <li><a href="/privacy" class="text-white-50">Privacy Policy</a></li>
                        <li><a href="/terms" class="text-white-50">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Connect</h6>
                    <div>
                        <a href="#" class="text-white-50 me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white-50 me-2"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white-50 me-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white-50"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-muted">
                <small>&copy; <?php echo date('Y'); ?> V4L (Vocal 4 Local). All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="/assets/js/app.js"></script>

    <!-- FK Autocomplete -->
    <script src="/assets/js/fk-autocomplete.js"></script>

    <!-- Form Validation -->
    <script>
        // Bootstrap form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>

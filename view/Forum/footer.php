    </div>
    <!-- Main Content Container End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 footer-about">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4">
                        <a href="/project-2a34/index.php?action=list" class="navbar-brand">
                            <h1 class="m-0 text-white">ZenCoders</h1>
                        </a>
                        <p class="mt-3 mb-4">A community forum for sharing ideas and discussions.</p>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Get In Touch</h3>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <p class="mb-0">123 Street, Tunisia</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">info@zencoders.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+216 71 123 456</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Quick Links</h3>
                            </div>
                            <div class="link-animated d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="/project-2a34/index.php?action=list"><i class="bi bi-arrow-right text-primary me-2"></i>Forum Home</a>
                                <a class="text-light mb-2" href="/project-2a34/index.php?action=createThread"><i class="bi bi-arrow-right text-primary me-2"></i>Create Thread</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-white" style="background: #061429;">
        <div class="container text-center">
            <div class="row justify-content-end">
                <div class="col-lg-8 col-md-6">
                    <div class="d-flex align-items-center justify-content-center" style="height: 75px;">
                        <p class="mb-0">&copy; <?php echo date('Y'); ?> <a class="text-white border-bottom" href="#">ZenCoders</a>. All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/project-2a34/public/lib/wow/wow.min.js"></script>
    <script src="/project-2a34/public/lib/easing/easing.min.js"></script>
    <script src="/project-2a34/public/lib/waypoints/waypoints.min.js"></script>
    <script src="/project-2a34/public/lib/counterup/counterup.min.js"></script>
    <script src="/project-2a34/public/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="/project-2a34/public/js/main.js"></script>
    
    <!-- Page Loader -->
    <script>
        $(window).on('load', function () {
            if ($('#spinner').length) {
                $('#spinner').delay(100).fadeOut('slow', function () {
                    $(this).remove();
                });
            }
        });
    </script>
</body>
</html>

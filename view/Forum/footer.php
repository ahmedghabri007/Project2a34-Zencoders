        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- [ Footer ] start -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> <a href="#">ZenCoders</a>. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 col-sm-12 text-md-end">
                    <p class="mb-0">A community forum for sharing ideas and discussions.</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- [ Footer ] end -->

    <!-- [ Back to top ] start -->
    <a href="#" class="to-top"><i class="ti ti-arrow-up"></i></a>

    <!-- Required Js -->    
    <script src="/project-2a34/public/mantis-assets/js/vendor-all.min.js"></script>
    <script src="/project-2a34/public/mantis-assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/project-2a34/public/mantis-assets/js/pcoded.min.js"></script>
    <script src="/project-2a34/public/mantis-assets/js/plugins/feather.min.js"></script>
    
    <!-- Mantis Template Scripts -->    
    <script src="/project-2a34/public/mantis-assets/js/menu-setting.min.js"></script>
    <script src="/project-2a34/public/mantis-assets/js/plugins/perfect-scrollbar.min.js"></script>
    
    <!-- Translation Javascript -->    
    <script src="/project-2a34/public/js/translation.js"></script>
    
    <!-- Page Initialization -->    
    <script type="text/javascript">
        // Initialize Mantis template features
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize feather icons
            feather.replace();
            
            // Remove loader
            setTimeout(function() {
                document.querySelector('.loader-bg').classList.add('fadeOut');
            }, 500);
        });
    </script>
</body>
</html>

            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Required Js -->
    <script src="../../public/mantis-assets/js/jquery.min.js"></script>
    <script src="../../public/mantis-assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/mantis-assets/js/perfect-scrollbar.min.js"></script>
    <script src="../../public/mantis-assets/js/menu-setting.min.js"></script>
    <script src="../../public/mantis-assets/js/pcoded.min.js"></script>
    
    <!-- DataTables -->
    <script src="../../public/mantis-assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../public/mantis-assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

    <!-- Custom Js -->
    <script type="text/javascript">
        // Initialize DataTables
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('.table')) {
                $('.table').DataTable().destroy();
            }
            
            $('.table').DataTable({
                pageLength: 10,
                responsive: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                language: {
                    search: '<i class="ti ti-search"></i>',
                    searchPlaceholder: 'Search records'
                }
            });
        });

        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>

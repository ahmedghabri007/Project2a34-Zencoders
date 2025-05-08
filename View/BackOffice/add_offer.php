<?php 
$pageTitle = "Add Travel Offer";
require_once __DIR__ . 'header.php';
require_once __DIR__ . 'sidebar.php';
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <?php require_once __DIR__ . 'topbar.php'; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Add a Travel Offer</h1>
            </div>

            <!-- Content Row -->
            <div class="row">
                <div class="col-xl-8 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <form id="addTravelOfferForm" action="/travel/process_offer" method="POST">
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input class="form-control form-control-user" type="text" id="title" name="title" minlength="3" required>
                                </div>

                                <div class="form-group">
                                    <label for="destination">Destination:</label>
                                    <input class="form-control form-control-user" type="text" id="destination" name="destination" pattern="[A-Za-z\s]{3,}" required>
                                </div>

                                <div class="form-group">
                                    <label for="departure_date">Departure Date:</label>
                                    <input class="form-control form-control-user" type="date" id="departure_date" name="departure_date" required>
                                </div>

                                <div class="form-group">
                                    <label for="return_date">Return Date:</label>
                                    <input class="form-control form-control-user" type="date" id="return_date" name="return_date" required>
                                </div>

                                <div class="form-group">
                                    <label for="price">Price:</label>
                                    <input class="form-control form-control-user" type="number" id="price" name="price" step="0.01" min="0" required>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="is_available" name="is_available" value="1">
                                        <label class="custom-control-label" for="is_available">Available</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="category">Category:</label>
                                    <select class="form-control form-control-user" id="category" name="category" required>
                                        <option value="adventure">Adventure</option>
                                        <option value="relaxation">Relaxation</option>
                                        <option value="culture">Culture</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">Add Offer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php 
include __DIR__ . '/header.php';

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/eventmodel.php';
require_once __DIR__ . '/../../Controller/eventcontroler.php';

$eventController = new eventcontroller();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventData = [
        'EventName' => $_POST['EventName'],
        'Date' => $_POST['Date'],
        'Time' => $_POST['Time'],
        'Adresse' => $_POST['Adresse'],
        'AttendeLimit' => $_POST['AttendeLimit'],
        'SponsoredBy' => $_POST['SponsoredBy'],
        'OrganisedBy' => $_POST['OrganisedBy'],
        'Activities' => $_POST['Activities']
    ];
    
    if ($eventController->addEvent($eventData)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Failed to add event.";
    }
}
?>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
    
    <!-- Main Content -->
      <div id="content">
         <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
        </nav>
        <!-- End of Topbar -->
        
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Add New Event</h1>
                <a href="index.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
                </a>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <!-- Content Row -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Event Details</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="EventName">Event Name</label>
                                    <input type="text" class="form-control" id="EventName" name="EventName" required>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="Date">Date</label>
                                        <input type="date" class="form-control" id="Date" name="Date" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="Time">Time</label>
                                        <input type="time" class="form-control" id="Time" name="Time" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="Adresse">Address</label>
                                    <input type="text" class="form-control" id="Adresse" name="Adresse" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="AttendeLimit">Attendee Limit</label>
                                    <input type="number" class="form-control" id="AttendeLimit" name="AttendeLimit" required>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="SponsoredBy">Sponsored By</label>
                                        <input type="text" class="form-control" id="SponsoredBy" name="SponsoredBy" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="OrganisedBy">Organized By</label>
                                        <input type="text" class="form-control" id="OrganisedBy" name="OrganisedBy" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="Activities">Activities</label>
                                    <textarea class="form-control" id="Activities" name="Activities" rows="4" required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Event
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
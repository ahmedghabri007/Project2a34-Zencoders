<?php 
include __DIR__ . '/header.php';

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/eventmodel.php';
require_once __DIR__ . '/../../Controller/eventcontroler.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$eventController = new eventcontroller();
$events = $eventController->showEvents();

if (!$event) {
    header("Location: index.php");
    exit();
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
                <h1 class="h3 mb-0 text-gray-800">Event Details</h1>
                <a href="index.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
                </a>
            </div>
            
            <!-- Content Row -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($event['EventName']) ?></h6>
                            <div>
                                <a href="editevent.php?id=<?= $event['id_event'] ?>" class="btn btn-warning btn-circle btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Date:</strong> <?= htmlspecialchars($event['Date']) ?></p>
                                    <p><strong>Time:</strong> <?= htmlspecialchars($event['Time']) ?></p>
                                    <p><strong>Address:</strong> <?= htmlspecialchars($event['Adresse']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Attendee Limit:</strong> <?= htmlspecialchars($event['AttendeLimit']) ?></p>
                                    <p><strong>Sponsored By:</strong> <?= htmlspecialchars($event['SponsoredBy']) ?></p>
                                    <p><strong>Organized By:</strong> <?= htmlspecialchars($event['OrganisedBy']) ?></p>
                                </div>
                            </div>
                            <hr>
                            <h5 class="font-weight-bold text-gray-800">Activities</h5>
                            <p><?= nl2br(htmlspecialchars($event['Activities'])) ?></p>
                            
                            <div class="mt-4">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
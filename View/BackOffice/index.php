<?php 
// Inclure le header
include __DIR__ . '/header.php';

// Inclure la navbar
 
// Inclure les classes nécessaires
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/eventmodel.php';
require_once __DIR__ . '/../../Controller/eventcontroler.php';

// Créer une instance du contrôleur
$eventController = new eventcontroller();
$events = $eventController->getAllEvents();
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
                <h1 class="h3 mb-0 text-gray-800">Events List</h1>
                <a href="addevent.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Add New Event
                </a>
            </div>
            
            <!-- Content Row -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">All Events</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover mt-3">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Nom de l'Événement</th>
      <th>Date</th>
      <th>Heure</th>
      <th>Adresse</th>
      <th>Limite de Participants</th>
      <th>Sponsorisé Par</th>
      <th>Organisé Par</th>
      <th>Activités</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($events as $event): ?>
      <tr>
        <td><?= htmlspecialchars($event['id_event']) ?></td>
        <td><?= htmlspecialchars($event['EventName']) ?></td>
        <td><?= htmlspecialchars($event['Date']) ?></td>
        <td><?= htmlspecialchars($event['Time']) ?></td>
        <td><?= htmlspecialchars($event['Adresse']) ?></td>
        <td><?= htmlspecialchars($event['AttendeLimit']) ?></td>
        <td><?= htmlspecialchars($event['SponsoredBy']) ?></td>
        <td><?= htmlspecialchars($event['OrganisedBy']) ?></td>
        <td><?= htmlspecialchars($event['Activities']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
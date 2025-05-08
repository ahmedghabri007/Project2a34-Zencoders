<?php
// Flag for header/footer to know this is the invite page
$invitePage = true;
$pageTitle  = 'invites List';

// Inclure le header
include __DIR__ . '/header.php';
include __DIR__ . '/headerT.php';
// Inclure les classes nécessaires
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/invitemodel.php'; // Replaced ticketmodel with invitemodel
require_once __DIR__ . '/../../Controller/invitecontroller.php'; // Replaced ticketcontroller with invitecontroller

// Créer une instance du contrôleur et récupérer tous les invites
$inviteController = new InviteController(); // Changed to InviteController
$invites = $inviteController->getAllInvites(); // Changed to getAllInvites
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
                <h1 class="h3 mb-0 text-gray-800">invites List</h1>
                <a href="addinvite.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Add New Invite
                </a>
            </div>
            
            <!-- Content Row -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">All invites</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table 
                                    class="table table-bordered" 
                                    id="dataTable" 
                                    width="100%" 
                                    cellspacing="0"
                                >
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Email</th>
                                            <th>Numéro de téléphone</th>
                                            <th>Événement ID</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($invites as $invite): ?>
                                      <tr>
                                          <td><?= htmlspecialchars($invite['id_invite']) ?></td>
                                          <td><?= htmlspecialchars($invite['nom']) ?></td>
                                          <td><?= htmlspecialchars($invite['prenom']) ?></td>
                                          <td><?= htmlspecialchars($invite['mail']) ?></td>
                                          <td><?= htmlspecialchars($invite['num_tele']) ?></td>
                                          <td><?= htmlspecialchars($invite['id_event']) ?></td>
                                          <td>
                                            <a 
                                              href="showinvite.php?id=<?= $invite['id_invite'] ?>" 
                                              class="btn btn-info btn-circle btn-sm"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a 
                                              href="editinvite.php?id=<?= $invite['id_invite'] ?>" 
                                              class="btn btn-warning btn-circle btn-sm"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a 
                                              href="deleteinvite.php?id=<?= $invite['id_invite'] ?>" 
                                              class="btn btn-danger btn-circle btn-sm" 
                                              onclick="return confirm('Are you sure you want to delete this invite?');"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </a>
                                          </td>
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
</div>

<?php include __DIR__ . '/footer.php'; ?>

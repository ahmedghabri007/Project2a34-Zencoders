<?php
// Inclure le header
include 'headert.php';

// Inclure la navbar
include 'navbar.php';

// Inclure les classes nécessaires
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/invitemodel.php'; // Model for invite

$inviteModel = new Invite(); // Create an instance of the Invite model
$invites = $inviteModel->getAllInvites(); // Fetch all invites
?>

<!-- Contenu principal -->
<div class="container-xxl py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gestion des invites</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Colonne principale -->
            <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.1s">
                <div class="border-bottom pb-3 mb-4">
                    <h1 class="display-5 mb-3 text-primary">Gestion des invites</h1>
                </div>

                <!-- Section invites -->
                <div class="mb-5">
                    <h4>👥 Liste des invites</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Numéro</th>
                                    <th>Événement ID</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invites as $invite): ?>
                                <tr>
                                    <td><?= htmlspecialchars($invite['id_invite']) ?></td>
                                    <td><?= htmlspecialchars($invite['name']) ?></td>
                                    <td><?= htmlspecialchars($invite['email']) ?></td>
                                    <td><?= htmlspecialchars($invite['phone_number']) ?></td>
                                    <td><?= htmlspecialchars($invite['id_event']) ?></td>
                                    <td>
                                        <a href="editinvite.php?id=<?= $invite['id_invite'] ?>" class="btn btn-sm btn-warning">✏️ Modifier</a>
                                        <a href="deleteinvite.php?id=<?= $invite['id_invite'] ?>" class="btn btn-sm btn-danger">🗑️ Supprimer</a>
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

<?php 
// Inclure le footer
include 'footert.php';
?>

<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/invitemodel.php';  // Replace with the Invite model
require_once __DIR__ . '/../../Controller/invitecontroller.php'; // Controller for managing invites

$inviteModel = new Invite();
$invites = $inviteModel->getAllInvites();  // Fetch all invites
?>

<?php include 'headert.php'; ?> <!-- Use front header -->

<div class="container py-5">
    <h2 class="mb-4 text-center">Nos invites</h2>

    <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
        <div class="alert alert-success text-center">
            ✅ Inscription réussie !
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach ($invites as $invite): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($invite['name']) ?></h5>
                    <p class="card-text">
                        <strong>Email:</strong> <?= htmlspecialchars($invite['email']) ?><br>
                        <strong>Numéro:</strong> <?= htmlspecialchars($invite['phone_number']) ?><br>
                        <strong>Événement ID:</strong> <?= htmlspecialchars($invite['id_event']) ?>
                    </p>
                    <a href="invitedetails.php?id=<?= $invite['id_invite'] ?>" class="btn btn-outline-primary w-100">
                        Voir les détails
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footert.php'; ?> <!-- Use front footer -->

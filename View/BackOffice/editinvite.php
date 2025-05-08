<?php 
include __DIR__ . '/header.php';
 
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/InviteModel.php';
require_once __DIR__ . '/../../Controller/InviteController.php';

$inviteController = new InviteController();

if (!isset($_GET['id'])) {
    header("Location: invitelistback.php");
    exit();
}

$invite = $inviteController->getInviteById($_GET['id']);

if (!$invite) {
    header("Location: invitelistback.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inviteData = [
        'nom' => $_POST['nom'],  // Changed from 'name'
        'prenom' => $_POST['prenom'],  // Added prenom field
        'mail' => $_POST['mail'],  // Changed from 'email'
        'num_tele' => $_POST['num_tele'],  // Changed from 'phone'
        'id_event' => $_POST['id_event']
    ];
    
    if ($inviteController->updateInvite($_GET['id'], $inviteData)) {
        header("Location: invitelistback.php?id=" . $_GET['id']);
        exit();
    } else {
        $error = "Échec de la mise à jour de l'invité.";
    }
}
?>
<body id="page-top">

<div id="wrapper">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
        </nav>

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Modifier invité</h1>
                <a href="invitelistback.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la liste
                </a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Détails invité</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="nom">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom"
                                           value="<?= htmlspecialchars($invite['nom']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom"
                                           value="<?= htmlspecialchars($invite['prenom']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="mail">Email</label>
                                    <input type="email" class="form-control" id="mail" name="mail"
                                           value="<?= htmlspecialchars($invite['mail']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="num_tele">Téléphone</label>
                                    <input type="text" class="form-control" id="num_tele" name="num_tele"
                                           value="<?= htmlspecialchars($invite['num_tele']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="id_event">ID de l'Événement</label>
                                    <input type="number" class="form-control" id="id_event" name="id_event"
                                           value="<?= htmlspecialchars($invite['id_event']) ?>" required>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                                <a href="showinvite.php?id=<?= $invite['id_invite'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php';
include __DIR__ . '/footerT.php'; ?>
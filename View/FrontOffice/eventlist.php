<?php
require_once __DIR__ . '/../../Controller/eventcontroler.php';
require_once __DIR__ . '/../../Model/eventmodel.php';
require_once __DIR__ . '/../../config.php';

$eventModel = new Event(); // Create instance of the Event class
$events = $eventModel->getAllEvents();
?>

<?php include '../BackOffice/header.php'; ?>

<!-- ✅ Success & ❌ Deletion Messages -->
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div style="color: green; font-weight: bold; margin-bottom: 15px;">
        ✅ L'événement a été ajouté avec succès !
    </div>
<?php endif; ?>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
    <div style="color: red; font-weight: bold; margin-bottom: 15px;">
        🗑️ L'événement a été supprimé avec succès !
    </div>
<?php endif; ?>

<?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
    <div style="color: blue; font-weight: bold; margin-bottom: 15px;">
        🔄 L'événement a été mis à jour avec succès !
    </div>
<?php endif; ?>

<table border="1">
    <tr>
        <th>Event Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Adresse</th>
        <th>Attende Limit</th>
        <th>Sponsored By</th>
        <th>Organised By</th>
        <th>Activities</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($events as $event): ?>
    <tr>
        <td><?= htmlspecialchars($event['EventName']) ?></td>
        <td><?= htmlspecialchars($event['Date']) ?></td>
        <td><?= htmlspecialchars($event['Time']) ?></td>
        <td><?= htmlspecialchars($event['Adresse']) ?></td>
        <td><?= htmlspecialchars($event['AttendeLimit']) ?></td>
        <td><?= htmlspecialchars($event['SponsoredBy']) ?></td>
        <td><?= htmlspecialchars($event['OrganisedBy']) ?></td>
        <td><?= htmlspecialchars($event['Activities']) ?></td>
        <td>
            <a href="../BackOffice/showevent.php?id-event=<?= htmlspecialchars($event['id-event']) ?>">👁️ Show</a> |
            <a href="../BackOffice/updateevent.php?id-event=<?= htmlspecialchars($event['id-event']) ?>">✏️ Edit</a> |
            <a href="../BackOffice/deleteevent.php?id-event=<?= htmlspecialchars($event['id-event']) ?>" onclick="return confirm('Delete this event?')">🗑️ Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include '../BackOffice/footer.php'; ?>

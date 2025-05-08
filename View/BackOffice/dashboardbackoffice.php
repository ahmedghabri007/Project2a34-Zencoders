<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/eventmodel.php';
require_once __DIR__ . '/../../Model/invitemodel.php';

$eventModel = new Event();
$events = $eventModel->getAllEvents();

$inviteModel = new Invite();
$invites = $inviteModel->getAllInvites();

// Prepare data for charts
$eventNames = [];
$attendeeLimits = [];
$inviteCounts = [];

foreach ($events as $event) {
    $eventNames[] = $event['EventName'];
    $attendeeLimits[] = $event['AttendeLimit'];
    
    // Count invites for this event
    $eventInvites = $inviteModel->getInvitesByEventId($event['id_event']);
    $inviteCounts[] = count($eventInvites);
}
?>

<?php include 'header.php'; 
include __DIR__ . '/headerT.php'; ?>

<div class="container py-5">
    <h2 class="mb-4 text-center">🎫 Gestion des Billets & Événements</h2>

    <!-- Charts Section -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📊 Invitations vs Capacité des Événements</h6>
                </div>
                <div class="card-body">
                    <canvas id="invitesVsCapacityChart" height="100"></canvas>
                </div>
                <div class="card-footer small text-muted">
                    Comparaison entre le nombre d'invitations envoyées et la capacité maximale de chaque événement
                </div>
            </div>
        </div>
    </div>

    <!-- Events Section -->
    <div class="mb-5">
        <h4>📅 Événements</h4>
        <a href="addevent.php" class="btn btn-primary">➕ Ajouter un événement</a>
        
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Adresse</th>
                        <th>Capacité</th>
                        <th>Invitations</th>
                        <th>Taux de Remplissage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): 
                        $eventInvites = $inviteModel->getInvitesByEventId($event['id_event']);
                        $inviteCount = count($eventInvites);
                        $fillRate = $event['AttendeLimit'] > 0 ? round(($inviteCount / $event['AttendeLimit']) * 100) : 0;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($event['id_event']) ?></td>
                        <td><?= htmlspecialchars($event['EventName']) ?></td>
                        <td><?= htmlspecialchars($event['Date']) ?></td>
                        <td><?= htmlspecialchars($event['Time']) ?></td>
                        <td><?= htmlspecialchars($event['Adresse']) ?></td>
                        <td><?= htmlspecialchars($event['AttendeLimit']) ?></td>
                        <td><?= $inviteCount ?></td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar <?= $fillRate > 80 ? 'bg-danger' : ($fillRate > 50 ? 'bg-warning' : 'bg-success') ?>" 
                                     role="progressbar" 
                                     style="width: <?= $fillRate ?>%" 
                                     aria-valuenow="<?= $fillRate ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <?= $fillRate ?>%
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="editevent.php?id=<?= $event['id_event'] ?>" class="btn btn-sm btn-warning">✏️</a>
                            <a href="deleteevent.php?id=<?= $event['id_event'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Invites vs Capacity Chart
const ctx = document.getElementById('invitesVsCapacityChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($eventNames) ?>,
        datasets: [
            {
                label: 'Capacité Maximale',
                data: <?= json_encode($attendeeLimits) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Invitations Envoyées',
                data: <?= json_encode($inviteCounts) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Nombre de Personnes'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Événements'
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    afterBody: function(context) {
                        const dataIndex = context[0].dataIndex;
                        const capacity = <?= json_encode($attendeeLimits) ?>[dataIndex];
                        const invites = <?= json_encode($inviteCounts) ?>[dataIndex];
                        const percentage = capacity > 0 ? Math.round((invites / capacity) * 100) : 0;
                        return `Taux de remplissage: ${percentage}%`;
                    }
                }
            }
        }
    }
});
</script>

<?php include 'footerT.php'; ?>
<?php
session_start();
require_once '../../Controller/BackOfficeController.php';
require_once '../../Controller/AuthController.php';

$auth = new AuthController();
if (!$auth->isAdmin()) {
    header('Location: /project-2a34/view/login.php');
    exit();
}

$backOffice = new BackOfficeController();
$stats = $backOffice->getDashboardStats();

include 'header.php';
?>

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="ti ti-messages" style="font-size: 2rem;"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="mb-0"><?php echo $stats['totalThreads']; ?></h3>
                    <p class="mb-0">Total Threads</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stats-card success">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="ti ti-message-dots" style="font-size: 2rem;"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="mb-0"><?php echo $stats['totalComments']; ?></h3>
                    <p class="mb-0">Total Comments</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stats-card warning">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="ti ti-message-2-share" style="font-size: 2rem;"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="mb-0"><?php echo $stats['activeThreads']; ?></h3>
                    <p class="mb-0">Active Threads</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stats-card danger">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="ti ti-message-2-off" style="font-size: 2rem;"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h3 class="mb-0"><?php echo $stats['inactiveThreads']; ?></h3>
                    <p class="mb-0">Inactive Threads</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Threads -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="ti ti-messages text-primary"></i>
                    Recent Threads
                </h5>
                <a href="threads.php" class="btn btn-sm btn-primary">
                    <i class="ti ti-eye"></i>
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['recentThreads'] as $thread): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-message-2 text-primary me-2"></i>
                                        <?php echo htmlspecialchars($thread['sujet']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-calendar text-muted me-2"></i>
                                        <?php echo date('Y-m-d H:i', strtotime($thread['date_publication'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $status = isset($thread['status']) ? $thread['status'] : 'active';
                                    $statusClass = $status === 'active' ? 'success' : 'danger';
                                    $statusIcon = $status === 'active' ? 'check' : 'x';
                                    ?>
                                    <span class="badge badge-<?php echo $statusClass; ?>">
                                        <i class="ti ti-<?php echo $statusIcon; ?>"></i>
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Comments -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="ti ti-message-dots text-primary"></i>
                    Recent Comments
                </h5>
                <a href="comments.php" class="btn btn-sm btn-primary">
                    <i class="ti ti-eye"></i>
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Thread</th>
                                <th>Comment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['recentComments'] as $comment): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-message-2 text-primary me-2"></i>
                                        <?php echo htmlspecialchars($comment['thread_title']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $preview = strlen($comment['comment']) > 30 
                                        ? htmlspecialchars(substr($comment['comment'], 0, 30)) . '...' 
                                        : htmlspecialchars($comment['comment']);
                                    ?>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-message text-muted me-2"></i>
                                        <?php echo $preview; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $status = isset($comment['status']) ? $comment['status'] : 'active';
                                    $statusClass = $status === 'active' ? 'success' : 'danger';
                                    $statusIcon = $status === 'active' ? 'check' : 'x';
                                    ?>
                                    <span class="badge badge-<?php echo $statusClass; ?>">
                                        <i class="ti ti-<?php echo $statusIcon; ?>"></i>
                                        <?php echo ucfirst($status); ?>
                                    </span>
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

<?php include 'footer.php'; ?>

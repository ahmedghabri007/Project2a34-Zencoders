<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../config/config.php';

$currentBadge = null;
$successMessage = '';
$errorMessage = '';
$upgradeHistory = [];

try {
    $pdo = Config::getConnexion();
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verificationId = $_POST['verification_id'] ?? null;
    $docType        = trim($_POST['document_type'] ?? '');
    $docNumber      = trim($_POST['document_number'] ?? '');

    if ($verificationId) {
        $stmt = $pdo->prepare("SELECT * FROM trustbadge WHERE verification_id = ?");
        $stmt->execute([$verificationId]);

        if ($stmt->rowCount() > 0) {
            $currentBadge = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmtHist = $pdo->prepare("SELECT * FROM badge_upgrade WHERE trustbadge_id = ? ORDER BY request_date DESC");
            $stmtHist->execute([$currentBadge['id']]);
            $upgradeHistory = $stmtHist->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $errorMessage = 'No trust badge found for this Verification ID.';
        }
    }

    if ($currentBadge && $docType && $docNumber) {
        if ($docType === $currentBadge['type_badge']) {
            $errorMessage = 'Document type must be different from current badge level.';
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO badge_upgrade
                 (trustbadge_id, new_document_type, new_document_number, request_status, request_date)
                 VALUES (?, ?, ?, 'PENDING', NOW())"
            );
            $stmt->execute([$currentBadge['id'], $docType, $docNumber]);
            $successMessage = 'Upgrade request submitted successfully.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Badge Upgrade Request</title>
  <link rel="stylesheet" href="../Front_Office/badge-upgrade.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f4fb;
      padding: 20px;
      color: #333;
    }

    h1 {
      color: #0056b3;
    }

    .message {
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
    }

    .error {
      background-color: #ffe6e6;
      color: #cc0000;
    }

    .success {
      background-color: #e6ffed;
      color: #007700;
    }

    .dashboard-wrapper {
      display: flex;
      gap: 20px;
      margin-top: 20px;
    }

    .left-panel, .center-panel, .right-panel {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 50, 0.1);
    }

    .left-panel { flex: 1.2; }
    .center-panel { flex: 1.5; }
    .right-panel { flex: 1; }

    h2 {
      margin-bottom: 10px;
      color: #003366;
    }

    label {
      display: block;
      margin-top: 10px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .submit-btn {
      margin-top: 15px;
      padding: 10px 15px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }

    .submit-btn:hover {
      background: #0056b3;
    }

    .upgrade-history table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .upgrade-history th, .upgrade-history td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }

    .upgrade-history th {
      background-color: #e0e7ff;
      color: #003366;
    }

    .status-pending {
      color: #d39e00;
      font-weight: bold;
    }

    .status-approved {
      color: #28a745;
      font-weight: bold;
    }

    .status-rejected {
      color: #dc3545;
      font-weight: bold;
    }

    .badge-image {
      margin-top: 10px;
      width: 100px;
      height: auto;
    }

    .error-message {
      color: red;
      font-size: 0.9em;
    }

    .success-message {
      color: #007700;
      font-weight: bold;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<h1>Request Badge Upgrade</h1>

<?php if ($errorMessage): ?>
  <div class="message error"><?= htmlspecialchars($errorMessage) ?></div>
<?php endif; ?>

<?php if ($successMessage): ?>
  <div class="message success"><?= htmlspecialchars($successMessage) ?></div>
<?php endif; ?>

<form method="POST" style="margin-bottom: 20px;">
  <label for="verification_id"><strong>Verification ID:</strong></label>
  <input
    type="number"
    name="verification_id"
    id="verification_id"
    value="<?= htmlspecialchars($_POST['verification_id'] ?? '') ?>"
    required
  >
  <button type="submit" class="submit-btn">Fetch Badge Info</button>
</form>

<?php if ($currentBadge): ?>
<div class="dashboard-wrapper">

  <div class="left-panel">
    <h2><i class="fas fa-id-badge"></i> Current Badge</h2>
    <p><strong>Type:</strong> <?= htmlspecialchars($currentBadge['type_badge']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($currentBadge['status']) ?></p>
    <p><strong>Confidence Level:</strong> <?= htmlspecialchars($currentBadge['niveau_confiance']) ?></p>
    <p><strong>Assigned Date:</strong> <?= htmlspecialchars($currentBadge['date_attribution']) ?></p>

    <?php
      $badgeType = strtolower($currentBadge['type_badge']);
      $badgePath = "../Front_Office/assets/badges/{$badgeType}.png";
      if (in_array($badgeType, ['bronze', 'silver', 'gold']) && file_exists($badgePath)):
    ?>
      <img src="<?= $badgePath ?>" alt="<?= ucfirst($badgeType) ?> Badge" class="badge-image">
    <?php endif; ?>
  </div>

  <div class="center-panel">
    <h2><i class="fas fa-upload"></i> Submit Upgrade Request</h2>
    <form method="POST" enctype="multipart/form-data" id="documentForm">
      <input type="hidden" name="verification_id" value="<?= htmlspecialchars($_POST['verification_id']) ?>">
      <label for="document_type">Document Type:</label>
      <select name="document_type" id="document_type" required>
        <option value="">-- Select Document Type --</option>
        <option value="Passport">Passport</option>
        <option value="National ID">National ID</option>
        <option value="Driver License">Driver License</option>
        <option value="Other">Other</option>
      </select>

      <label for="document_number">Document Number:</label>
      <input type="text" name="document_number" id="document_number" pattern="[A-Z0-9]{8,12}" placeholder="Ex: AB12345678" required>

      <label for="document_file">Upload File:</label>
      <input type="file" name="document_file" id="document_file" accept=".jpg,.jpeg,.png,.pdf">

      <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Submit</button>
    </form>
  </div>

  <div class="right-panel upgrade-history">
    <h2><i class="fas fa-history"></i> Upgrade History</h2>
    <?php if ($upgradeHistory): ?>
      <table>
        <thead>
          <tr>
            <th>Type</th>
            <th>Number</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($upgradeHistory as $entry): ?>
            <tr>
              <td><?= htmlspecialchars($entry['new_document_type']) ?></td>
              <td><?= htmlspecialchars($entry['new_document_number']) ?></td>
              <td class="<?php
                switch (strtolower($entry['request_status'])) {
                  case 'approved': echo 'status-approved'; break;
                  case 'rejected': echo 'status-rejected'; break;
                  default: echo 'status-pending';
                }
              ?>">
                <?= htmlspecialchars($entry['request_status']) ?>
              </td>
              <td><?= htmlspecialchars($entry['request_date']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No previous upgrade requests.</p>
    <?php endif; ?>
  </div>

</div>
<?php endif; ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('documentForm');
        const docTypeSelect = form?.querySelector('[name="document_type"]');
        const docNumberInput = form?.querySelector('[name="document_number"]');

        if (!form || !docTypeSelect || !docNumberInput) return;

        const errorMessages = {
            document_type: createErrorContainer(docTypeSelect.parentNode),
            document_number: createErrorContainer(docNumberInput.parentNode)
        };

        const validationRules = {
            document_type: {
                validate: value => value !== '',
                message: 'Veuillez sélectionner un type de document'
            },
            document_number: {
                validate: value => /^[A-Z0-9]{8,12}$/i.test(value),
                message: 'Le numéro doit contenir 8 à 12 caractères alphanumériques'
            }
        };

        docTypeSelect.addEventListener('change', () => validateField('document_type'));
        docNumberInput.addEventListener('input', debounce(() => validateField('document_number'), 300));

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const isFormValid = Object.keys(validationRules).every(field => validateField(field));
            if (isFormValid) {
                handleFormSubmission();
                this.submit();
            }
        });

        function validateField(field) {
            const value = form.elements[field].value.trim();
            const { validate, message } = validationRules[field];
            const isValid = field === 'document_number' ?
                value !== '' && validate(value) :
                validate(value);

            if (!isValid) {
                showError(field, message);
                return false;
            }
            clearError(field);
            return true;
        }

        function showError(field, message) {
            errorMessages[field].textContent = message;
            form.elements[field].classList.add('invalid');
        }

        function clearError(field) {
            errorMessages[field].textContent = '';
            form.elements[field].classList.remove('invalid');
        }

        function createErrorContainer(parent) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            parent.appendChild(errorDiv);
            return errorDiv;
        }

        function handleFormSubmission() {
            const submission = {
                type: docTypeSelect.value,
                number: docNumberInput.value.toUpperCase(),
                timestamp: new Date().toISOString()
            };
            showSuccessMessage('Document soumis avec succès !');
        }

        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message';
            successDiv.textContent = message;
            form.parentNode.insertBefore(successDiv, form.nextSibling);
            setTimeout(() => successDiv.remove(), 3000);
        }

        function debounce(func, wait) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    });
    </script>
</body>
</html>































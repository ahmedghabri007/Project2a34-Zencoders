<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projetelev";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch daily match statistics
$sql = "
    SELECT 
        DATE(date_created) AS match_date,
        COUNT(*) AS match_count
    FROM matches
    GROUP BY DATE(date_created)
    ORDER BY match_date DESC
";
$result = $conn->query($sql);

// Calculate total matches
$totalMatches = 0;
$dailyMatches = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dailyMatches[] = $row;
        $totalMatches += $row['match_count'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Match Statistics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #007bff; /* blue */
        }
        table {
            width: 60%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 18px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff; /* blue */
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .percentage {
            color: #007bff; /* blue */
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Daily Match Statistics</h1>

<table>
    <tr>
        <th>Date</th>
        <th>Matches</th>
        <th>Percentage</th>
    </tr>

    <?php if (!empty($dailyMatches)): ?>
        <?php foreach ($dailyMatches as $day): 
            $percentage = ($day['match_count'] / $totalMatches) * 100;
        ?>
            <tr>
                <td><?= htmlspecialchars($day['match_date']) ?></td>
                <td><?= htmlspecialchars($day['match_count']) ?></td>
                <td><span class="percentage"><?= number_format($percentage, 2) ?>%</span></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No match data available.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>

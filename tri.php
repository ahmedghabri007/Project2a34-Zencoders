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

$sortField = 'fullname'; // default
if (isset($_POST['sort_by'])) {
    $sortOption = $_POST['sort_by'];
    if (in_array($sortOption, ['age', 'gender', 'location'])) {
        $sortField = $sortOption;
    }
}

$sql = "SELECT * FROM profile ORDER BY $sortField ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sort Profiles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        form {
            text-align: center;
            margin-bottom: 30px;
        }
        select, button {
            padding: 8px 12px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 80%;
            margin: 0 auto;
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
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<h1>Sort Profiles</h1>

<form method="post" action="">
    <select name="sort_by">
        <option value="age" <?php if($sortField == 'age') echo 'selected'; ?>>Age</option>
        <option value="gender" <?php if($sortField == 'gender') echo 'selected'; ?>>Gender</option>
        <option value="location" <?php if($sortField == 'location') echo 'selected'; ?>>Location</option>
    </select>
    <button type="submit">Sort</button>
</form>

<table>
    <tr>
        <th>Full Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Location</th>
        <th>Profession</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['age']) ?></td>
                <td><?= htmlspecialchars($row['gender']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td><?= htmlspecialchars($row['profession']) ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No profiles found.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>

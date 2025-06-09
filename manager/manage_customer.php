<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "parking";

// Create DB connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form actions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        switch ($action) {
            case "ban":
                $conn->query("UPDATE companies SET banned = 1 WHERE id = $id");
                break;
            case "unban":
                $conn->query("UPDATE companies SET banned = 0 WHERE id = $id");
                break;
            case "delete":
                $conn->query("DELETE FROM companies WHERE id = $id");
                break;
        }
    }

    // Prevent form resubmission on refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch company data
$result = $conn->query("SELECT * FROM companies ORDER BY id DESC");
$companies = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Companies</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .btn { padding: 5px 10px; border: none; margin-right: 5px; cursor: pointer; }
        .ban { background-color: #f0ad4e; color: white; }
        .unban { background-color: #5cb85c; color: white; }
        .delete { background-color: #d9534f; color: white; }
        form.inline { display: inline; }
    </style>
    <script>
        function confirmDelete(form) {
            if (confirm("Are you sure you want to delete this company?")) {
                form.submit();
            }
        }
    </script>
</head>
<body>

<h2>Parking Companies Management</h2>

<?php if (count($companies) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Company Name</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Price</th>
                <th>Info</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($companies as $company): ?>
                <tr>
                    <td><?= htmlspecialchars($company['id']) ?></td>
                    <td><?= htmlspecialchars($company['org_name']) ?></td>
                    <td><?= htmlspecialchars($company['latitude']) ?></td>
                    <td><?= htmlspecialchars($company['longitude']) ?></td>
                    <td>$<?= htmlspecialchars($company['price']) ?></td>
                    <td><?= nl2br(htmlspecialchars($company['info'])) ?></td>
                    <td><?= $company['banned'] ? 'Banned' : 'Active' ?></td>
                    <td>
                        <?php if (!$company['banned']): ?>
                            <form method="post" class="inline">
                                <input type="hidden" name="id" value="<?= $company['id'] ?>">
                                <input type="hidden" name="action" value="ban">
                                <button class="btn ban" type="submit">Ban</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="inline">
                                <input type="hidden" name="id" value="<?= $company['id'] ?>">
                                <input type="hidden" name="action" value="unban">
                                <button class="btn unban" type="submit">Unban</button>
                            </form>
                        <?php endif; ?>

                        <form method="post" class="inline" onsubmit="event.preventDefault(); confirmDelete(this);">
                            <input type="hidden" name="id" value="<?= $company['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="btn delete" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No companies found in the database.</p>
<?php endif; ?>

</body>
</html>

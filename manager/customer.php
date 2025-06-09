<?php
session_start();
require_once 'db_connection.php'; // Initial DB connection ($conn)
require_once 'tables.php'; // Assumes it creates `companies` table

$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["org_name"] ?? "");
    $latitude = trim($_POST["latitude"] ?? "");
    $longitude = trim($_POST["longitude"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $info = trim($_POST["info"] ?? "");
    if (empty($name)) $errors[] = "Parking Name is required.";
    if (!is_numeric($latitude)) $errors[] = "Latitude must be a number.";
    if (!is_numeric($longitude)) $errors[] = "Longitude must be a number.";
    if (!is_numeric($price)) $errors[] = "Price must be a number.";
    if (empty($info)) $errors[] = "Additional information is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO companies (org_name, latitude, longitude, price, info) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sddds", $name, $latitude, $longitude, $price, $info);
            if ($stmt->execute()) {
                $dbSafeName = preg_replace('/[^a-zA-Z0-9_]/', '', $name);
                $newDatabase = $dbSafeName . "_db";
                $createDbSQL = "CREATE DATABASE IF NOT EXISTS `$newDatabase`";

                if (!$conn->query($createDbSQL)) {
                    $errors[] = "Database creation failed: " . $conn->error;
                } else {
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $new_db = new mysqli($servername, $username, $password, $newDatabase);

                    if ($new_db->connect_error) {
                        $errors[] = "Connection to new DB failed: " . $new_db->connect_error;
                    } else {
                        // STEP 5: Create admin_data table
                        $createAdminTable = "
                            CREATE TABLE IF NOT EXISTS admin_data (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                org_name VARCHAR(255) NOT NULL,
                                password VARCHAR(255) NOT NULL,
                                banned BOOLEAN DEFAULT FALSE,
                                latitude DOUBLE NOT NULL,
                                longitude DOUBLE NOT NULL,
                                price DECIMAL(10,2) NOT NULL,
                                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                            )
                        ";

                        if (!$new_db->query($createAdminTable)) {
                            $errors[] = "Creating admin_data table failed: " . $new_db->error;
                        } else {
                            $rawPassword = mt_rand(100000, 999999);
                            $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);
                            $banned = false;

                            $insertAdmin = $new_db->prepare("INSERT INTO admin_data (org_name, password, banned, latitude, longitude, price) VALUES (?, ?, ?, ?, ?, ?)");
                            if ($insertAdmin) {
                                $insertAdmin->bind_param("ssiddd", $name, $hashedPassword, $banned, $latitude, $longitude, $price);
                                if ($insertAdmin->execute()) {
                                    $successMessage = "Company and admin created successfully.<br><br>
                                        You can now log in with:<br>
                                        Username: <strong>$name</strong><br>
                                        Password: <strong>$rawPassword</strong><br>
                                        (Please change your password after first login)";
                                } else {
                                    $errors[] = "Admin insert failed: " . $insertAdmin->error;
                                }
                                $insertAdmin->close();
                            } else {
                                $errors[] = "Admin insert prepare failed: " . $new_db->error;
                            }
                        }
                        $new_db->close();
                    }
                }
            } else {
                $errors[] = "Insert into companies failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Prepare insert into companies failed: " . $conn->error;
        }
    }
} else {
    $errors[] = "Invalid request method.";
}

$conn->close();
?>

<!-- HTML Response -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Setup Result</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .message { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .success { background-color: #e6ffed; color: #2f855a; border: 1px solid #c6f6d5; }
        .error { background-color: #ffe6e6; color: #cc0000; border: 1px solid #f5c2c2; }
    </style>
</head>
<body>

<?php if (!empty($errors)): ?>
    <div class="message error">
        <h3>Errors occurred:</h3>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php elseif (!empty($successMessage)): ?>
    <div class="message success">
        <h3>Success!</h3>
        <p><?= $successMessage ?></p>
    </div>
<?php endif; ?>

<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
<?php 
require_once 'db_connection.php';
// Create the parking_info table if it doesn't exist
$createTableSql ="
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    org_name VARCHAR(255) NOT NULL,
    banned BOOLEAN DEFAULT FALSE,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
";
$created= $conn->query($createTableSql);
if (!$conn->query($createTableSql)) {
    die("Error creating table: " . $conn->error);
}
?>
<?php
require_once 'db_connection.php';
// Create table SQL
$sql = "CREATE TABLE IF NOT EXISTS managers (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute query
if (mysqli_query($conn, $sql)) {
    $username= 'admin';
    $password = password_hash('admin', PASSWORD_DEFAULT); // Hash the password
    $email="admin@gmail.com";
    $phone="0912345678";
    // Insert default admin user
    $insert_sql = "INSERT INTO managers (username, email, password,phone) VALUES ('$username', '$email', '$password', '$phone')";
    if (mysqli_query($conn, $insert_sql)) {
        echo "Default admin user created successfully.";
    } else {
        echo "Error inserting default admin user: " . mysqli_error($conn);
        exit;
    }
} else if (mysqli_errno($conn) == 1060) {
} else {
    echo "Error creating table: " . mysqli_error($conn);
    exit;
}
mysqli_close($conn);
?>
<html>
    <body>
        <h1>Success</h1>
    </body>
</html>
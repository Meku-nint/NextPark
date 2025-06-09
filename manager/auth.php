<?php
require_once 'db_connection.php'; // Include your DB connection file
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        // Missing fields — redirect with error
        header('Location: login.php?error=1');
        exit;
    }

    // Prepare SQL statement to get user by username (or email)
    $stmt = $conn->prepare("SELECT id, username, password FROM managers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password against the hashed password in DB
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];

            header('Location: dashboard.php');
            exit;
        } else {
            // Wrong password
            header('Location: login.php?error=1');
            exit;
        }
    } else {
        // Username not found
        header('Location: login.php?error=1');
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed without POST
    header('Location: login.php');
    exit;
}

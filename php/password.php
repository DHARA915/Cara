<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "Cara"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Basic validation
    if (!empty($userPassword) && !empty($confirmPassword)) {
        // Check if passwords match
        if ($userPassword === $confirmPassword) {
            // Hash the password before storing it
            $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

            // Store the password in the database (you can use email or other fields for identification)
            $sql = "INSERT INTO users (password) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $hashedPassword); // Bind the hashed password

            if ($stmt->execute()) {
                echo "Password stored successfully!";
                header("Location: index.html"); // Redirect to a different page
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Passwords do not match!";
        }
    } else {
        echo "Please fill out both password fields.";
    }
}

// Close connection
$conn->close();
?>

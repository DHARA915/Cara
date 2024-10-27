<?php
// Start session
session_start();

// Database connection variables
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password is empty
$dbname = "Cara"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the registration table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Password is stored without hashing
    document_path VARCHAR(255) NOT NULL, -- Store the document path as a string
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Handle form submission for registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // File upload handling
    $document = $_FILES['document'];

    // Specify the directory where files will be uploaded
    $uploadDir = './uploads'; // Ensure this directory exists and is writable
    $documentPath = $uploadDir . basename($document['name']);

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($document['tmp_name'], $documentPath)) {
        // Prepare SQL statement to insert data
        $sql = "INSERT INTO registration (full_name, gender, email, phone, username, password, document) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $full_name, $gender, $email, $phone, $username, $password, $document);

        // Execute and check for success
        if ($stmt->execute()) {
            echo "<script>
                    alert('Registration successful! You can now login.');
                    window.location.href = 'http://localhost/php/Cara/';
                  </script>";
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error uploading document.";
    }

    $stmt->close();
}

// Close connection
$conn->close();
?>

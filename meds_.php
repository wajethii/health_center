<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding a patient
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_med_btn'])) {
    // Validate and sanitize input data
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $quantity = intval($_POST['quantity']);
   
    $user_id = 1; // Assuming the user ID is 1 for simplicity, replace with actual logic

    // Check if contact number already exists
    $stmt = $conn->prepare("SELECT * FROM medications WHERE medication_id = ?");
    $stmt->bind_param("s", $medication_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Medication already exists
        $_SESSION['status'] = "Error: The medication is already was added recently.";
        $stmt->close();
    } else {
        // Prepare and bind the SQL statement
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO medications (medication_id, name, price, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isii", $medication_id, $name, $price, $quantity);

        // Execute the statement and handle success or error
        if ($stmt->execute()) {
            $_SESSION['status'] = "Medication added successfully!";
        } else {
            $_SESSION['status'] = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Redirect to the patients page
    header("Location: pharmacy.php");
    exit();
}

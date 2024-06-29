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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_patient_btn'])) {
    // Validate and sanitize input data
    $name = trim($_POST['name']);
    $reason = trim($_POST['reason']);
    $age = intval($_POST['age']);
    $gender = trim($_POST['gender']);
    $contact = trim($_POST['contact']);
    $medical_history = trim($_POST['medical_history']);
    $contact_kin = trim($_POST['contact_kin']);
    $user_id = 1; // Assuming the user ID is 1 for simplicity, replace with actual logic

    // Check if contact number already exists
    $stmt = $conn->prepare("SELECT * FROM patients WHERE contact = ?");
    $stmt->bind_param("s", $contact);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Contact number already exists
        $_SESSION['status'] = "Error: The contact number is already owned by someone else.";
        $stmt->close();
    } else {
        // Prepare and bind the SQL statement
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO patients (user_id, name, reason, age, gender, contact, medical_history, contact_kin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ississss", $user_id, $name, $reason, $age, $gender, $contact, $medical_history, $contact_kin);

        // Execute the statement and handle success or error
        if ($stmt->execute()) {
            $_SESSION['status'] = "Patient added successfully!";
        } else {
            $_SESSION['status'] = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Redirect to the patients page
    header("Location: patients.php");
    exit();
}

// Handle form submission for deleting a patient
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_patient_btn'])) {
    $patient_id = intval($_POST['patient_id']);

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);

    // Execute the statement and handle success or error
    if ($stmt->execute()) {
        $_SESSION['status'] = "Patient deleted successfully!";
    } else {
        $_SESSION['status'] = "Error deleting patient.";
    }

    // Close the statement
    $stmt->close();

    // Redirect to the patients page
    header("Location: patients.php");
    exit();
}

// Close the database connection
$conn->close();

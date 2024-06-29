<?php
include_once "dbcon.php";

if (isset($_GET['medication_id'])) {
    $medication_id = intval($_GET['medication_id']);
    
    $sql = "SELECT quantity, price FROM medications WHERE medication_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $medication_id);
    $stmt->execute();
    $stmt->bind_result($quantity, $price);
    $stmt->fetch();
    $stmt->close();
    
    echo json_encode(["quantity" => $quantity, "price" => $price]);
    exit(); // Stop further execution after responding to the AJAX request
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $mpesacode = $_POST['mpesacode'];
    $total_quantity = $_POST['total_quantity'];
    $total_amount = $_POST['total_amount'];
    
    // Insert the prescription record
    $sql = "INSERT INTO prescriptions (patient_id, doctor_id, mpesacode, total_quantity, total_amount) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisid", $patient_id, $doctor_id, $mpesacode, $total_quantity, $total_amount);
    $stmt->execute();
    $stmt->close();
    
    // Insert each medication in the prescription
    foreach ($_POST['medications'] as $medication) {
        $medication_id = $medication['medication_id'];
        $quantity = $medication['quantity'];
        
        $sql = "INSERT INTO prescription_medications (prescription_id, medication_id, quantity) VALUES (LAST_INSERT_ID(), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $medication_id, $quantity);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: prescriptions.php"); // Redirect to a success page
    exit();
}
?>

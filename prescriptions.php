<?php
session_start(); // Start the session

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop further execution
}
include "includes/header.php";
?>
<div class="container mx-auto max-w-7xl mt-5 px-4">
    <!-- Button and Heading -->
    <div class="mx-auto max-w-7xl  flex justify-between mb-4">
        <div>
            <h5 class="text-xl font-normal">Prescriptions</h5>
        </div>
        <div class="flex items-center">
            <button
                class="bg-cyan-500 text-white hover:bg-cyan-400 font-semibold py-2 px-4 rounded shadow-xl focus:outline-none focus:shadow-outline flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                    class="bi bi-plus text-white mr-2" viewBox="0 0 16 16">
                    <path
                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
                <a href="issuemd.php">prescription</a>
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php
        include_once "dbcon.php";

        // Retrieve unique patients
        $sql = "SELECT DISTINCT pt.patient_id, pt.name as patient_name, pt.contact FROM prescriptions p
                JOIN patients pt ON p.patient_id = pt.patient_id
                ORDER BY pt.patient_id"; // Order by patient id
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col bg-white shadow-md rounded-sm px-2 ">
                        <div class="p-4">
                            <div x-data="{ open: false }" class="relative">
                                <div @click="open = !open" class="w-full bg-white text-gray-700 font-medium rounded-sm mb-2 cursor-pointer flex items-center justify-between">
                                    <span class="mr-2">' . $row["patient_name"] . ' ' . $row["contact"] . '</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
</svg>
                                </div>
                                <div x-show="open" class="accordion-body">';

                // Retrieve prescriptions for the current patient
                $prescriptionSql = "SELECT p.*, d.name as doctor_name FROM prescriptions p
                                    JOIN doctors d ON p.doctor_id = d.doctor_id
                                    WHERE p.patient_id = " . $row["patient_id"] . "
                                    ORDER BY p.prescription_date"; // Order by prescription date
                $prescriptionResult = $conn->query($prescriptionSql);

                if ($prescriptionResult->num_rows > 0) {
                    while ($prescriptionRow = $prescriptionResult->fetch_assoc()) {
                        // Display prescription details
                        echo '<div x-data="{ open: false }" class="relative">
                                <div @click="open = !open" class="w-full bg-white-50 text-gray-700  rounded-sm mb-2 cursor-pointer flex items-center justify-between">
                                    <span class="mr-2">Date (' . $prescriptionRow["prescription_date"] . ')</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
</svg>
                                </div>
                                <div x-show="open" class="accordion-body text-gray-700 ">
                                    <p>Doctor:<strong> ' . $prescriptionRow["doctor_name"] . '</strong></p>
                                    <p>Amount incl VAT:<strong> $' . number_format($prescriptionRow["total_amount"], 2) . '</strong></p>
                                    <p>Mpesa Code:<strong> ' . $prescriptionRow["mpesacode"] . '</strong></p>
                                    <p>Date:<strong> ' . $prescriptionRow["prescription_date"] . '</strong></p>
                                </div>
                            </div>';
                    }
                }

                echo '</div></div></div></div>'; // Close accordion and card divs
            }
        }
        ?>
    </div>
</div>
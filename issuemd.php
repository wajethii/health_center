<?php
session_start(); // Start the session

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop further execution
}
include "includes/header.php";

// Include the database connection file
include_once "dbcon.php";
?>
<!-- Issue Medication Modal -->
<div x-data="{ open: true }">
    <div x-show="open" x-cloak class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full p-6">
        <div class="flex justify-end">
        <span class="cursor-pointer text-gray-500 text-lg" onclick="closeModal()">&times;</span>
    </div>
            <form action="pharmacy_.php" method="POST" id="medication-form">
                <div class="mb-4">
                    <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient:</label>
                    <select id="patient_id" name="patient_id" required class="mt-1 block w-full border border-gray-300 rounded-sm ">
                        <option value="">Select Patient</option>
                        <?php
                        $sql = "SELECT patient_id, name FROM patients";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['patient_id'] . "'>" . $row['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor:</label>
                    <select id="doctor_id" name="doctor_id" required class="mt-1 block w-full border border-gray-300 rounded-sm">
                        <option value="">Select Doctor</option>
                        <?php
                        $sql = "SELECT doctor_id, name FROM doctors";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['doctor_id'] . "'>" . $row['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div id="medications-container">
                    <div class="medication-group mb-4">
                        <label for="medication_id_1" class="block text-sm font-medium text-gray-700">Medication:</label>
                        <select id="medication_id_1" name="medications[0][medication_id]" required class="medication-select mt-1 block w-full border border-gray-300 rounded-sm">
                            <option value="">Select Medication</option>
                            <?php
                            $sql = "SELECT medication_id, name FROM medications";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['medication_id'] . "'>" . $row['name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <label for="quantity_1" class="block text-sm font-medium text-gray-700">Quantity:</label>
                        <input type="text" id="quantity_1" name="medications[0][quantity]" readonly class="quantity-input mt-1 block w-full border border-gray-300 rounded-sm">
                    </div>
                </div>

                <button type="button" id="add-medication" class="bg-white text-green-700 hover:text-green-400 font-medium mb-4">Add Medication</button>

                <div class="mb-4">
                    <label for="mpesacode" class="block text-sm font-medium text-gray-700">Mpesa Code</label>
                    <input type="text" id="mpesacode" name="mpesacode" class="mt-1 block w-full border border-gray-300 rounded-sm">
                </div>

                <div class="mb-4">
                    <label for="total_quantity" class="block text-sm font-medium text-gray-700">Total Quantity:</label>
                    <input type="text" id="total_quantity" name="total_quantity" readonly class="mt-1 block w-full border border-gray-300 rounded-sm">
                </div>

                <div class="mb-4">
                    <label for="total_amount" class="block text-sm font-medium text-gray-700">Total Amount (including 20% VAT):</label>
                    <input type="text" id="total_amount" name="total_amount" readonly class="mt-1 block w-full border border-gray-300 rounded-sm">
                </div>

                <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-800 text-white font-bold py-2 px-4 rounded">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let medicationIndex = 1;
    let totalQuantity = 0;
    let totalAmount = 0;

    document.getElementById('add-medication').addEventListener('click', function () {
        medicationIndex++;
        const medicationsContainer = document.getElementById('medications-container');
        const newMedicationGroup = document.createElement('div');
        newMedicationGroup.classList.add('medication-group', 'mb-4');
        newMedicationGroup.innerHTML = `
            <label for="medication_id_${medicationIndex}" class="block text-sm font-medium text-gray-700">Medication:</label>
            <select id="medication_id_${medicationIndex}" name="medications[${medicationIndex - 1}][medication_id]" required class="medication-select mt-1 block w-full border border-gray-300 rounded-sm">
                <option value="">Select Medication</option>
                <?php
                $sql = "SELECT medication_id, name FROM medications";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['medication_id'] . "'>" . $row['name'] . "</option>";
                    }
                }
                ?>
            </select>
            <label for="quantity_${medicationIndex}" class="block text-sm font-medium text-gray-700">Quantity:</label>
            <input type="text" id="quantity_${medicationIndex}" name="medications[${medicationIndex - 1}][quantity]" readonly class="quantity-input mt-1 block w-full border border-gray-300 rounded-sm">
        `;
        medicationsContainer.appendChild(newMedicationGroup);
        addQuantityListener(medicationIndex);
    });

    function addQuantityListener(index) {
        document.getElementById(`medication_id_${index}`).addEventListener('change', function () {
            const medicationId = this.value;
            const quantityInput = document.getElementById(`quantity_${index}`);
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const response = JSON.parse(this.responseText);
                    quantityInput.value = response.quantity;
                    
                    // Update total quantity and amount
                    totalQuantity += parseInt(response.quantity);
                    totalAmount += parseFloat(response.price);
                    
                    // Calculate total amount including VAT
                    const totalAmountWithVAT = totalAmount * 1.20;

                    // Update total quantity and amount fields
                    document.getElementById('total_quantity').value = totalQuantity;
                    document.getElementById('total_amount').value = totalAmountWithVAT.toFixed(2);
                }
            };
            xhr.open("GET", "pharmacy_.php?medication_id=" + medicationId, true);
            xhr.send();
        });
    }

    // Initialize the first medication select listener
    addQuantityListener(1);
});
</script>
<script>
    // Open modal function
    function openModal() {
        document.getElementById("openAddModal").classList.remove("hidden");
    }

    // Close modal function
    function closeModal() {
        window.location.href = "patients.php"; // Redirect to patients.php
    }
</script>
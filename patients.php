<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop further execution
}

include "includes/header.php";
?>
<!-- Button and Heading -->
<div class="flex justify-between mx-auto max-w-7xl px-4 sm:px-6 lg:px-4 mt-8">
    <div>
        <h5 class="text-xl font-normal">Our patients</h5>
    </div>
    <div class="flex items-center">
        <button class="bg-cyan-500 text-white hover:bg-cyan-400 font-semibold py-2 px-4 rounded shadow-xl focus:outline-none focus:shadow-outline flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus text-white mr-2" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
            </svg>
            <a href="addpatient.php" class="text-white no-underline">patient</a>
        </button>
    </div>
</div>
<div x-data="{ openPatientModal: false, selectedPatient: {} }" class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-4 mt-8">
    <div class="mx-auto bg-white shadow-lg rounded-md">
        <!-- Fetch patient records with pagination -->
        <?php
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

        // Set the number of records per page
        $recordsPerPage = 15;

        // Determine the current page number
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $currentPage = intval($_GET['page']);
        } else {
            $currentPage = 1;
        }

        // Calculate the SQL LIMIT offset
        $offset = ($currentPage - 1) * $recordsPerPage;

        // Fetch patient records with pagination
        $sql = "SELECT p.*, u.username FROM patients p LEFT JOIN users u ON p.user_id = u.user_id LIMIT $offset, $recordsPerPage";
        $result = $conn->query($sql);

        // Fetch total number of patients
        $totalPatientsSql = "SELECT COUNT(*) AS total FROM patients";
        $totalPatientsResult = $conn->query($totalPatientsSql);
        $totalPatientsRow = $totalPatientsResult->fetch_assoc();
        $totalPatients = $totalPatientsRow['total'];

        // Calculate total number of pages
        $totalPages = ceil($totalPatients / $recordsPerPage);
        ?>

        <!-- Table to display patient records -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Reason</th>
                        <th class="px-4 py-2 text-left">Age</th>
                        <th class="px-4 py-2 text-left">Gender</th>
                        <th class="px-4 py-2 text-left">Contact</th>
                        <th class="px-4 py-2 text-left">History</th>
                        <th class="px-4 py-2 text-left">Kin</th>
                        <th class="px-4 py-2 text-left">Added By</th>
                        <th class="px-4 py-2 text-left">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick='viewPatientDetails(" . json_encode($row) . ")' class='cursor-pointer hover:bg-gray-100'>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td class='px-4 py-2 overflow-hidden text-ellipsis whitespace-nowrap' style='max-width: 200px;'>" . htmlspecialchars($row['reason']) . "</td>";
                            echo "<td class='px-4 py-2'>" . $row['age'] . "</td>";
                            echo "<td class='px-4 py-2'>" . $row['gender'] . "</td>";
                            echo "<td class='px-4 py-2'>" . $row['contact'] . "</td>";
                            echo "<td class='px-4 py-2 overflow-hidden text-ellipsis whitespace-nowrap' style='max-width: 200px;'>" . ($row['medical_history']) . "</td>";
                            echo "<td class='px-4 py-2'>" . $row['contact_kin'] . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td class='px-4 py-2'>" . $row['created_at'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='px-4 py-2'>No patients found</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-4 mb-4">
            <nav class="inline-flex rounded-xl shadow-sm -space-x-px" aria-label="Pagination">
                <!-- Previous Page Button -->
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?php echo ($currentPage - 1); ?>"
                        class="relative inline-flex items-center m-5 px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Previous
                    </a>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>"
                        class="relative inline-flex items-center m-5 px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 <?php echo ($currentPage == $i) ? 'font-bold' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <!-- Next Page Button -->
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?php echo ($currentPage + 1); ?>"
                        class="relative inline-flex items-center m-5 px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Next
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</div>


<!-- Patient Details Modal -->
<div id="viewPatientModal"
    class="fixed z-10 inset-0 flex items-center justify-center min-h-full px-4 py-12 sm:px-6 lg:px-8 hidden">
    <div class="bg-gray-500 bg-opacity-75 fixed inset-0 transition-opacity" aria-hidden="true"></div>
    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full mx-4 max-w-2xl">
        <!-- Close Button -->
        <button onclick="closeViewPatientModal()"
            class="absolute top-0 right-0 mt-4 mr-4 text-gray-700 hover:text-gray-900 focus:outline-none">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="bg-white px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Patient Details</h3>
            <div class="mt-2">
                <dl>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-gray-700 text-sm font-bold mb-2">Name:</dt>
                            <dd id="patientName" class="text-gray-700 leading-tight"></dd>
                        </div>
                        <div>
                            <dt class="text-gray-700 text-sm font-bold mb-2">Reason:</dt>
                            <dd id="patientReason" class="text-gray-700 leading-tight"></dd>
                        </div>
                        <div>
                            <dt class="text-gray-700 text-sm font-bold mb-2">Age:</dt>
                            <dd id="patientAge" class="text-gray-700 leading-tight"></dd>
                        </div>
                        <div>
                            <dt class="text-gray-700 text-sm font-bold mb-2">Gender:</dt>
                            <dd id="patientGender" class="text-gray-700 leading-tight"></dd>
                        </div>
                        <div>
                            <dt class="text-gray-700 text-sm font-bold mb-2">Contact:</dt>
                            <dd id="patientContact" class="text-gray-700 leading-tight"></dd>
                        </div>
                        <div>
                            <dt class="text-gray-700 text-sm font-bold mb-2">Medical History:</dt>
                            <dd id="patientHistory" class="text-gray-700 leading-tight"></dd>
                        </div>
                        <div>
                            <dt class="text-gray-700 text-sm font-bold mb-2">Contact Kin:</dt>
                            <dd id="patientKin" class="text-gray-700 leading-tight"></dd>
                        </div>
                        <div>
                            <dt class="text-gray-700 text-sm font-bold mb-2">Added By:</dt>
                            <dd id="patientAddedBy" class="text-gray-700 leading-tight"></dd>
                        </div>
                        <div>
                            <dt class="text-gray-700 text-sm font-bold mb-2">Time:</dt>
                            <dd id="patientTime" class="text-gray-700 leading-tight"></dd>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
        <div class="bg-white px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse mb-6">

            <form action="patients_.php" method="post" class="ml-3">
                <input type="hidden" name="patient_id" id="patientId">
                <button type="submit" name="delete_patient_btn"
                    class="w-full inline-flex justify-center rounded-md border border-transparent bg-white text-base font-medium text-red-600  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Delete
                </button>
            </form>
            <form action="patients_.php" method="post" class="ml-3">
                <input type="hidden" name="patient_id" id="patientId">
                <button type="submit" name="update_patient_btn"
                    class="w-full inline-flex justify-center rounded-md border border-transparent bg-white text-base font-medium text-green-700  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Edit
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Function to open the modal for viewing patient details
    function viewPatientDetails(patient) {
        // Update the content of the modal with the patient details
        document.getElementById('patientName').innerText = patient.name;
        document.getElementById('patientReason').innerText = patient.reason;
        document.getElementById('patientAge').innerText = patient.age;
        document.getElementById('patientGender').innerText = patient.gender;
        document.getElementById('patientContact').innerText = patient.contact;
        document.getElementById('patientHistory').innerText = patient.medical_history;
        document.getElementById('patientKin').innerText = patient.contact_kin;
        document.getElementById('patientAddedBy').innerText = patient.username;
        document.getElementById('patientTime').innerText = patient.created_at;
        document.getElementById('patientId').value = patient.patient_id;

        // Show the modal
        document.getElementById('viewPatientModal').classList.remove('hidden');
    }

</script>

<script>
    // Open modal function
    function openModal() {
        document.getElementById("viewPatientModal").classList.remove("hidden");
    }

    // Close modal function
    function closeModal() {
        window.location.href = "patients.php"; // Redirect to doctors.php
    }
</script>

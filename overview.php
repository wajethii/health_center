<?php
session_start(); // Start the session

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop further execution
}

include "includes/header.php";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/// Fetch total number of doctors
$doctorQuery = "SELECT COUNT(*) AS total_doctors FROM doctors";
$doctorResult = $conn->query($doctorQuery);
$doctorRow = $doctorResult->fetch_assoc();
$totalDoctors = $doctorRow['total_doctors'];

// Fetch total number of patients
$patientQuery = "SELECT COUNT(*) AS total_patients FROM patients";
$patientResult = $conn->query($patientQuery);
$patientRow = $patientResult->fetch_assoc();
$totalPatients = $patientRow['total_patients'];


// Fetch doctors' data
$sql = "SELECT name, image_url, specialization FROM doctors LIMIT 4";
$result = $conn->query($sql);

// Define the working hours (for simplicity, we're assigning the same hours to all)
$working_hours = "10am - 4pm";

?>

<style>
    /* Add this to your CSS */
    .border-r {
        border-right: 1px solid #e2e8f0;
    }

    /* Custom styling for calendar */
    #calendar-container {
        border: none;
        /* Remove border */
    }

    /* Custom styling for current date */
    .today {
        background-color: cyan-500;
        /* Cyan-500 background */
        color: white;
        /* White text color */
    }
</style>

<div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-4 mt-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="grid grid-cols-1 sm:grid-cols-3 rounded gap-2 bg-white shadow-md mt-5">
                <!-- Doctors Card -->
                <div class="p-4 border-r sm:border-b-0 flex items-center">
                    <div class="icon mr-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                            class="bi bi-hospital text-cyan-500" viewBox="0 0 16 16" stroke-width="1">
                            <path
                                d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1zM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25z" />
                            <path
                                d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1zm2 14h2v-3H7zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1zm0-14H6v1h4zm2 7v7h3V8zm-8 7V8H1v7z" />
                        </svg>
                    </div>
                    <div class="text">
                        <h2 class="text-lg font-semibold"><?php echo $totalDoctors; ?>+</h2>
                        <p class="text-base text-gray-900 mr-2">Doctors</p>
                        <p class="text-sm text-gray-400">Today</p>
                    </div>
                </div>
                <!-- Patients Card -->
                <div class="p-4 border-r sm:border-b-0 flex items-center">
                    <div class="icon mr-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                            class="bi bi-people text-cyan-500" viewBox="0 0 16 16">
                            <path
                                d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                        </svg>
                    </div>
                    <div class="text">
                        <h2 class="text-lg font-semibold"><?php echo $totalPatients; ?>+</h2>
                        <p class="text-base text-gray-900 mr-2">Patients</p>
                        <p class="text-sm text-gray-400">Today</p>
                    </div>
                </div>
                <!-- Urgent Card -->
                <div class="p-4 flex items-center">
                    <div class="icon mr-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                            class="bi bi-exclamation-circle text-cyan-500" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                            <path
                                d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                        </svg>
                    </div>
                    <div class="text">
                        <h2 class="text-lg font-semibold">48+</h2>
                        <p class="text-base text-black mr-2">Urgent Resolve</p>
                        <p class="text-sm text-gray-400">Today</p>
                    </div>
                </div>
            </div>
            <div class="mx-auto max-w-7xl flex justify-between mt-5 mb-2">
                <div>
                    <h5 class="text-lg font-semibold">Today's roster</h5>
                </div>
                <div>
                    <a href="doctors.php" class="text-base underline hover:underline-offset-4">View All</a>
                </div>
            </div>
            <div class="container text-left mt-2">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '
                            <div class="col">
                                <div class="card bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out" style="width: 100%;">
                                    <img src="' . $row["image_url"] . '" class="doctor-image rounded-t-lg" alt="Doctor Image" style="height: 200px; object-fit: cover;">
                                    <div class="p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="font-semibold text-base md:text-base lg:text-base">' . $row["name"] . '</h4>
                                        </div>
                                        <p class="text-gray-800 mb-2">' . $row["specialization"] . '</p>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock text-gray-500 mr-2" viewBox="0 0 16 16">
                                                <path d="M8 3.5a.5.5 0 0 1 .5.5v4.25H12a.5.5 0 0 1 0 1H8a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5zm0-2.5a7 7 0 1 1 0 14A7 7 0 0 1 8 1zm0 1a6 6 0 1 0 0 12A6 6 0 0 0 8 2z"/>
                                            </svg>
                                            <span class="text-sm font-semibold text-gray-400">' . $working_hours . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<p>No doctors found.</p>';
                    }
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
        <div class="relative mt-5">
            <div class="max-w-lg rounded bg-white mx-auto p-4">



                <!-- Add Event button -->
                <button id="add-event-btn"
                    class="w-full mt-5 px-4 py-2 bg-cyan-500 text-white rounded-lg shadow-md hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Add Event
                </button>
            </div>

            <div class="max-w-lg mx-auto">
                <h1 class="text-lg font-semibold mt-4 mb-2">Inbox</h1>

                <div class="bg-white rounded-lg">
                    <!-- Unread message -->
                    <div class="p-4 border-b border-gray-200 hover:bg-gray-100 cursor-pointer">
                        <div class="flex justify-between items-center">
                            <div class="text-base font-semibold text-gray-900">Unread Message 1</div>
                            <div class="text-sm text-gray-500">2 min ago</div>
                        </div>
                        <div class="text-sm text-gray-700 mt-2">
                            This is a brief preview of the unread message content.
                        </div>
                    </div>

                    <!-- Unread message -->
                    <div class="p-4 border-b border-gray-200 hover:bg-gray-100 cursor-pointer">
                        <div class="flex justify-between items-center">
                            <div class="text-base font-semibold text-gray-900">Unread Message 2</div>
                            <div class="text-sm text-gray-500">5 min ago</div>
                        </div>
                        <div class="text-sm text-gray-700 mt-2">
                            This is another preview of an unread message.
                        </div>
                    </div>

                    <!-- Read message -->
                    <div class="p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div class="text-base font-normal text-gray-700">Read Message 1</div>
                            <div class="text-sm text-gray-500">1 day ago</div>
                        </div>
                        <div class="text-sm text-gray-600 mt-2">
                            This is a preview of a read message content.
                        </div>
                    </div>

                    <!-- Read message -->
                    <div class="p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div class="text-base font-normal text-gray-700">Read Message 2</div>
                            <div class="text-sm text-gray-500">2 days ago</div>
                        </div>
                        <div class="text-sm text-gray-600 mt-2">
                            This is another preview of a read message.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
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

// Set the limit of cards per page
$cardsPerPage = 8;

// Determine the current page number
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = intval($_GET['page']);
} else {
    $currentPage = 1;
}

// Calculate the SQL LIMIT offset
$offset = ($currentPage - 1) * $cardsPerPage;

// Fetch doctors data from the database with pagination
$sql = "SELECT * FROM doctors LIMIT $offset, $cardsPerPage";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output doctors data
    echo '<div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-4 mt-8">
    <div class="container mx-auto">
        <!-- Button and Heading -->
        <div class="mx-auto max-w-7xl  flex justify-between mb-5">
            <div>
                <h5 class="text-lg font-semibold">Our doctors</h5>
            </div>
             <div class="flex items-center">
        <button class="bg-cyan-500 text-white hover:bg-cyan-400 font-semibold py-2 px-4 rounded shadow-xl focus:outline-none focus:shadow-outline flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus text-white mr-2" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
            </svg>
            <a href="adddoctor.php" class="text-white no-underline">Doctor</a>
        </button>
    </div>
        </div>
        <!-- Your content goes here -->
        <div class="container text-left mt-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">';

    // Loop through each row of doctors data
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col">
                    <div class="card bg-white rounded-lg shadow-lg hover:shadow-xl transition duration-300 ease-in-out" style="width: 100%;">
                        <img src="' . $row["image_url"] . '" class="doctor-image rounded-t-lg" alt="Doctor Image" style="height: 200px; object-fit: cover;">
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-semibold text-base md:text-xl lg:text-lg">' . $row["name"] . '</h4>
                                <span class="text-sm text-green-500 font-semibold">' . $row["contract_type"] . '</span>
                            </div>
                            <p class="text-sm font-semibold text-gray-400">' . $row["specialization"] . '</p>
                        </div>
                    </div>
                </div>';
    }

    echo '</div>
        </div>';

    // Pagination
    // Fetch total number of doctors
    $totalDoctorsSql = "SELECT COUNT(*) AS total FROM doctors";
    $totalDoctorsResult = $conn->query($totalDoctorsSql);
    $totalDoctorsRow = $totalDoctorsResult->fetch_assoc();
    $totalDoctors = $totalDoctorsRow['total'];

    // Calculate total number of pages
    $totalPages = ceil($totalDoctors / $cardsPerPage);

    // Pagination HTML
    echo '<div class="flex justify-center mt-4">
            <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">';

    // Previous Page Button
    if ($currentPage > 1) {
        echo '<a href="?page=' . ($currentPage - 1) . '" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>';
    }

    // Page Numbers
    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<a href="?page=' . $i . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 ' . (($currentPage == $i) ? 'font-bold' : '') . '">' . $i . '</a>';
    }

    // Next Page Button
    if ($currentPage < $totalPages) {
        echo '<a href="?page=' . ($currentPage + 1) . '" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>';
    }

    echo '</nav>
        </div>';

    echo '</div>
    </div>';
} else {
    echo "No doctors found.";
}

// Close the database connection
$conn->close();
?>
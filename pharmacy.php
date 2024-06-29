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

// Fetch medications data from the database with pagination
$sql = "SELECT name, price, quantity FROM medications LIMIT $offset, $recordsPerPage";
$result = $conn->query($sql);

// Fetch total number of records
$totalRecordsSql = "SELECT COUNT(*) AS total FROM medications";
$totalRecordsResult = $conn->query($totalRecordsSql);
$totalRecordsRow = $totalRecordsResult->fetch_assoc();
$totalRecords = $totalRecordsRow['total'];

// Calculate total number of pages
$totalPages = ceil($totalRecords / $recordsPerPage);
?>

<div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-4 mt-8">
    <!-- Button and Heading -->
    <div class="mx-auto max-w-7xl flex justify-between px-2 mt-8">
    <div>
        <h5 class="text-xl font-normal">Our pharmacy</h5>
    </div>
    <div class="flex items-center">
        <button class="bg-cyan-500 text-white hover:bg-cyan-400 font-semibold py-2 px-2 rounded shadow-xl focus:outline-none focus:shadow-outline flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus text-white mr-2" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
            </svg>
            <a href="meds.php" class="text-white no-underline">medication</a>
        </button>
    </div>
</div>

    <div class="mx-auto bg-white shadow-md max-w-7xl px-4 sm:px-6 lg:px-4 mt-8">
        <!-- Search Bar -->
        <div class="mb-4 py-4">
            <input type="text" id="searchInput" class="bg-gray-50 border rounded-md px-4 py-2 w-full"
                placeholder="Search...">
            <div id="searchSuggestions" class="bg-white border rounded-md mt-1 hidden"></div>
        </div>
        <!-- Medication table -->
        <table class="table-auto border-collapse w-full">
            <thead>
                <tr>
                    <th class="border px-4 py-2 text-left">Medication</th>
                    <th class="border px-4 py-2 text-left">Price</th>
                    <th class="border px-4 py-2 text-left">Quantity</th>
                </tr>
            </thead>
            <tbody id="medicationsTableBody">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['price']) . "</td>";
            if ($row['quantity'] == 0) {
                echo "<td class='border px-4 py-2 text-red-500'>";
                echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-emoji-frown' viewBox='0 0 16 16'>";
                echo "<path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16'/>";
                echo "<path d='M4.285 12.433a.5.5 0 0 0 .683-.183A3.5 3.5 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.5 4.5 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5'/>";
                echo "</svg> Out of Stock";
                echo "</td>";
            } else {
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['quantity']) . "</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='border px-4 py-2'>No medications found</td></tr>";
    }
    ?>
</tbody>

        </table>

        <!-- Pagination -->
        <div class="flex justify-center mt-4">
            <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <!-- Previous Page Button -->
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?php echo ($currentPage - 1); ?>"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 <?php echo ($currentPage == $i) ? 'font-bold' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <!-- Next Page Button -->
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?php echo ($currentPage + 1); ?>"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const medicationsTableBody = document.getElementById('medicationsTableBody');

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        if (query.length === 0) {
            searchSuggestions.innerHTML = '';
            searchSuggestions.classList.add('hidden');
            medicationsTableBody.classList.remove('hidden');
            return;
        }
        fetch(`search.php?query=${query}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const suggestions = data.map(item => `<div>${item.name}</div>`).join('');
                    searchSuggestions.innerHTML = suggestions;
                    searchSuggestions.classList.remove('hidden');
                    medicationsTableBody.classList.add('hidden');
                } else {
                    searchSuggestions.innerHTML = '<div>No suggestions found</div>';
                    searchSuggestions.classList.remove('hidden');
                    medicationsTableBody.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error fetching search results:', error));
    });
</script>
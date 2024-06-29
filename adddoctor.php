
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mtaani-Hospital</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.2.6/dist/cdn.min.js" defer></script>

    <script src="//unpkg.com/alpinejs" defer></script> <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
 <!-- The Modal Background -->
 <div class="modal-background fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50"></div>

<!-- The Modal Content -->
<div id="myModal" class="modal-content bg-white mx-auto my-12 rounded-lg p-6 max-w-lg w-full relative z-50">
    <div class="flex justify-end">
        <span class="close cursor-pointer text-gray-500 text-lg" onclick="closeModal()">&times;</span>
    </div>
    <h2 class="text-lg font-bold mb-4">Add New Doctor</h2>
    <form action="doctors_.php" method="POST" id="addDoctorForm" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name:</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization:</label>
            <select id="specialization" name="specialization" class="mt-1 block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-sm text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="Nutritionist">Nutritionist</option>
                <option value="Dentist">Dentist</option>
                <option value="Surgeon">Surgeon</option>
                <option value="Cardiologist">Cardiologist</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload Image:</label>
            <input type="file" name="image" id="image" class="mt-1 block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="contract_type" class="block text-sm font-medium text-gray-700 mb-1">Contract Type:</label>
            <input type="text" id="contract_type" name="contract_type" class="mt-1 block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
            <input type="email" id="email" name="email" class="mt-1 block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone:</label>
            <input type="text" id="phone" name="phone" class="mt-1 block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-800 text-white font-bold py-2 px-4 rounded">Submit</button>
    </form>
</div>

<script>
    // Open modal function
    function openModal() {
        document.getElementById("myModal").classList.remove("hidden");
    }

    // Close modal function
    function closeModal() {
        window.location.href = "doctors.php"; // Redirect to doctors.php
    }
</script>

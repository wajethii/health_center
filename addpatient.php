

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
 <div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50"></div>

<!-- Add Patient Modal -->
<div x-data="{ openAddModal: true }">
    <div x-show="openAddModal" x-cloak class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full p-6">
        <div class="flex justify-end">
        <span class="cursor-pointer text-gray-500 text-lg" onclick="closeModal()">&times;</span>
    </div>
            <form action="patients_.php" method="POST" class="w-full max-w-lg mx-auto">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="name">Name:</label>
                    <input
                        class="block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" type="text" name="name" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="reason">Reason:</label>
                    <textarea
                        class="block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="reason" name="reason" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="age">Age:</label>
                    <input
                        class="block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="age" type="number" name="age">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="gender">Gender:</label>
                    <select
                        class="block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="gender" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="contact">Contact:</label>
                    <input
                        class="block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="contact" type="tel" name="contact">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="medical_history">Medical History:</label>
                    <textarea
                        class="block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="medical_history" name="medical_history" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700" for="contact_kin">Contact Kin:</label>
                    <input
                        class="block w-full border border-gray-300 rounded-sm py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="contact_kin" type="tel" name="contact_kin" required>
                </div>
                <div class="bg-gray-50 w-full items-center justify-center">
                    <button type="submit" name="add_patient_btn"
                        class="w-full bg-cyan-500 hover:bg-cyan-800 text-white font-bold py-2 px-4 rounded mb-2">
                        Add
                    </button>
                    <button type="button" @click="openAddModal = false; window.location.href='patients.php';"
                        class="w-full bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 rounded py-2 px-4">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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
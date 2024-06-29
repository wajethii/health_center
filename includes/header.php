<?php
$loggedIn = isset($_SESSION['username']); // Check if the user is logged in
?>

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
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Include Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>

    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body>
    <nav class="bg-white" x-data="{ open: false, profileOpen: false }">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                    <button type="button"
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:text-cyan-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false" @click="open = !open">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>
                        <!-- Icon when menu is closed. -->
                        <svg class="block h-6 w-6" width="24" height="24" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" aria-hidden="true" :class="{ 'hidden': open }">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <!-- Icon when menu is open. -->
                        <svg class="hidden h-6 w-6" width="24" height="24" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                            :class="{ 'block': open, 'hidden': !open }">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex flex-shrink-0 items-center">
                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=cyan&shade=500"
                            alt="Your Company">
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4">
                            <a href="overview.php"
                                class="px-3 py-2 text-base font-medium text-gray-700 hover:text-cyan-500">Dashboard</a>
                            <a href="doctors.php"
                                class="rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:text-cyan-500">Doctors</a>
                            <a href="patients.php"
                                class="rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:text-cyan-500">Patients</a>
                            <a href="pharmacy.php"
                                class="rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:text-cyan-500">Pharmacy</a>
                            <a href="prescriptions.php"
                                class="rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:text-cyan-500">Prescriptions</a>
                            <a href="events.php"
                                class="rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:text-cyan-500">Calendar</a>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <!-- Sign In/Sign Up or Profile Dropdown -->
                    <?php if ($loggedIn): ?>
                        <!-- Profile dropdown -->
                        <div class="relative ml-3" x-data="{ profileOpen: false }">
                            <div>
                                <button type="button"
                                    class="relative flex items-center rounded-full text-base focus:outline-none"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true"
                                    @click="profileOpen = !profileOpen">
                                    <span class="absolute -inset-1.5"></span>
                                    <span class="sr-only">Open user menu</span>
                                    <div class="flex items-center space-x-2">
                                        <!-- User name and role -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                            class="bi bi-bell text-gray-700 hover:text-cyan-500" viewBox="0 0 16 16">
                                            <path
                                                d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
                                        </svg>
                                        <div class="text-gray-700 hover:text-cyan-500 font-medium">
                                            <?php echo $_SESSION['username']; ?></div>
                                        <!-- Profile image -->
                                        <img class="h-8 w-8 rounded-full"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                            alt="">
                                    </div>
                                </button>
                            </div>
                            <!-- Dropdown menu, show/hide based on menu state -->
                            <div class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white shadow-md py-1"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                                x-show="profileOpen" @click.away="profileOpen = false" x-cloak>
                                <a href="#" class="block px-4 py-2 text-base text-gray-700 hover:text-cyan-500"
                                    role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
                                <a href="#" class="block px-4 py-2 text-base text-gray-700 hover:text-cyan-500"
                                    role="menuitem" tabindex="-1" id="user-menu-item-1">Users</a>
                                <a href="logout.php" class="block px-4 py-2 text-base text-gray-700 hover:text-cyan-500"
                                    role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex space-x-4">
                            <a href="login.php"
                                class="text-base text-gray-700 hover:text-cyan-500 font-bold py-2 px-2">Login</a>
                            <a href="register.php"
                                class="text-base text-gray-700 hover:text-cyan-500 font-bold py-2 px-2">Sign Up</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Mobile menu, show/hide based on menu state -->
        <div class="sm:hidden" id="mobile-menu" x-show="open" @click.away="open = false">
            <div class="space-y-1 px-2 pb-3 pt-2 bg-cyan-500">
                <a href="overview.php"
                    class="block rounded-md px-3 py-2 text-base font-medium text-white hover:text-gray-200"
                    aria-current="page">Dashboard</a>
                <a href="doctors.php"
                    class="block rounded-md px-3 py-2 text-base font-medium text-white hover:text-gray-200">Doctors</a>
                <a href="patients.php"
                    class="block rounded-md px-3 py-2 text-base font-medium text-white hover:text-gray-200">Patients</a>
                <a href="pharmacy.php"
                    class="block rounded-md px-3 py-2 text-base font-medium text-white hover:text-gray-200">Pharmacy</a>
                <a href="prescriptions.php"
                    class="block rounded-md px-3 py-2 text-base font-medium text-white hover:text-gray-200">Prescriptions</a>
                <a href="events.php"
                    class="block rounded-md px-3 py-2 text-base font-medium text-white hover:text-gray-200">Calendar</a>
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-8 max-w-7xl px-4">
    <div class="relative h-auto shadow-md">
        <!-- Image overlay -->
        <img class="absolute inset-0 w-full h-full object-cover" src="/images/nurse.jpg" alt="Dashboard overview"
            style="max-height: 240px; width: 100%; opacity: 0.2;">
        
        <!-- Text overlay -->
        <div class="relative z-10 mt-5 bg-gradient-to-b from-cyan-500 to-transparent bg-opacity-75 p-5 rounded">
            <h6 class="text-white font-medium">Welcome back</h6>
            <h5 class="text-white text-base font-bold"><?php echo $_SESSION['username']; ?></h5>
            <p class="text-white">Administrators can monitor patient admissions, manage staff schedules and access
                real-time data analytics, ensuring the highest standards of care and operational excellence.</p>
        </div>
    </div>
</div>


</body>

</html>
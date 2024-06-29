<?php
include 'dbcon.php';

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $contact_info = $_POST['contact_info'];

    // Check if contact info already exists in the database
    $check_query = "SELECT * FROM users WHERE contact_info = '$contact_info'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $message = "Error: Contact info already exists";
    } else {
        // Contact info is not in the database, proceed with registration
        $sql = "INSERT INTO users (role, username, contact_info, password) VALUES ('$role', '$username',  '$contact_info', '$password')";

        if ($conn->query($sql) === TRUE) {
            $message = "New user registered successfully";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
include"includes/header.php";
?>

<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-md w-full bg-white shadow-md rounded px-8 py-8">
        <?php if (!empty($message)) : ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $message; ?></span>
        </div>
    <?php endif; ?>
        <h2 class="text-2xl font-bold mb-4">Sign up</h2>
        <form method="post">
        <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="role">Role:</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="role" name="role">
                    <option value="Doctor">Doctor</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="username" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="contact_info">Contact Info:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="contact_info" type="text" name="contact_info" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" required>
            </div>
            <div>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full focus:outline-none focus:shadow-outline" type="submit">Register</button>
            </div>
            <div class="mt-4 text-center">
            <p>Already have an account?<a href="login.php" class="text-indigo-500 hover:text-indigo-700 px-3">Sign in</a></p>
            </div>
        </form>
    </div>
</div>


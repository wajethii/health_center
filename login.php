<?php
include 'dbcon.php';

session_start();

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $message = "Login successful. Welcome, $username";
        header("Location: overview.php");
        exit(); // Ensure no further execution of script after redirection
    } else {
        $message = "Invalid username or password";
    }
}

include "includes/header.php";
$conn->close();
?>

<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8" x-data="{ message: '<?php echo $message; ?>' }">
    <div class="max-w-md w-full bg-white shadow-md rounded px-8 py-8">
        <h4 class="text-2xl font-bold mb-4">Welcome back</h4>

        <template x-if="message">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline" x-text="message"></span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="message = ''">
                    <span class="text-green-500">&times;</span>
                </button>
            </div>
        </template>

        <form method="post">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" name="username" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" required>
            </div>
            <div>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full focus:outline-none focus:shadow-outline" type="submit">Login</button>
            </div>
            <div class="mt-4 text-center">
            <p>Don't have an account?<a href="register.php" class="text-indigo-500 hover:text-indigo-700 px-3">Sign up</a></p>
            </div>
        </form>
    </div>
</div>
</body>

</html>

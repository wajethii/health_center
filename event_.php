<?php
// Database connection settings
$host = 'localhost'; // MySQL host
$dbname = 'healthcare'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Create a PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Handle POST requests to add/edit events
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    $title = $data['title'];
    $start = $data['start'];
    $end = $data['end'];
    $description = $data['description']; // Optional

    // Prepare statement for inserting data
    $stmt = $pdo->prepare("INSERT INTO events (title, start, end, description) VALUES (:title, :start, :end, :description)");

    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':start', $start);
    $stmt->bindParam(':end', $end);
    $stmt->bindParam(':description', $description);

    // Execute statement
    if ($stmt->execute()) {
        $response = array(
            'status' => 'success',
            'message' => 'Event added successfully'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to add event'
        );
    }

    echo json_encode($response);
    exit;
}

// Handle DELETE requests to delete events
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $deleteParams);
    $eventId = $deleteParams['id'];

    // Prepare statement for deleting event
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
    $stmt->bindParam(':id', $eventId);

    // Execute statement
    if ($stmt->execute()) {
        $response = array(
            'status' => 'success',
            'message' => 'Event deleted successfully'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to delete event'
        );
    }

    echo json_encode($response);
    exit;
}

// Handle GET requests to fetch events
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Prepare statement for selecting events
    $stmt = $pdo->query("SELECT * FROM events");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($events);
    exit;
}
?>

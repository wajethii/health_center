<?php
// Include the database connection file
include_once "dbcon.php";

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Prepare the SQL statement to fetch medications starting with the query
    $sql = "SELECT name, price, quantity FROM medications WHERE name LIKE '$query%'";
    $result = $conn->query($sql);

    if ($result) {
        $medications = array();
        while ($row = $result->fetch_assoc()) {
            $medication = array(
                'name' => $row['name'],
                'price' => $row['price'],
                'quantity' => $row['quantity']
            );
            $medication['status'] = ($row['quantity'] == 0) ? 'Out of Stock' : 'In Stock';
            $medications[] = $medication;
        }
        echo json_encode($medications);
    } else {
        echo json_encode(array()); // Return an empty array if no matches found
    }
} else {
    echo json_encode(array()); // Return an empty array if query parameter is not set
}

?>

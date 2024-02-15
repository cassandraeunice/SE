<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafesienadb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the item number from the request parameters
$product_ID = isset($_GET['product_ID']) ? (int)$_GET['product_ID'] : 0;

// Sanitize the item number to prevent SQL injection
$product_ID = $conn->real_escape_string($product_ID);

// Fetch details of the specified item or all items from the database
$sql = ($product_ID > 0)
    ? "SELECT product_ID, product_img, product_name, product_description, product_price FROM menu WHERE product_ID = $product_ID"
    : "SELECT product_ID, product_img, product_name, product_description, product_price FROM menu";

$result = $conn->query($sql);

if (!$result) {
    // Handle query error
    echo "Error executing query: " . $conn->error;
} elseif ($result->num_rows > 0) {
    if ($product_ID > 0) {
        // Fetch data as associative array for a specific item
        $itemDetails = $result->fetch_assoc();

        // Read the image file into a variable
        $imagePath = "../images/" . $itemDetails['product_img'];
        $imageData = base64_encode(file_get_contents($imagePath));

        // Embed the image data into the JSON response
        $itemDetails['product_img'] = 'data:image/jpeg;base64,' . $imageData;

        // Output the JSON response
        echo json_encode($itemDetails);
    } else {
        // Fetch data for all items and return as JSON array with base64-encoded images
        $itemsArray = array();
        while ($row = $result->fetch_assoc()) {
            // Read the image file into a variable
            $imagePath = "../images/" . $row['product_img'];
            $imageData = base64_encode(file_get_contents($imagePath));

            // Embed the image data into the JSON response
            $row['product_img'] = 'data:image/jpeg;base64,' . $imageData;

            // Add the modified row to the array
            $itemsArray[] = $row;
        }

        // Output the JSON response
        echo json_encode($itemsArray);
    }
} else {
    echo "Item(s) not found";
}

$conn->close();
?>

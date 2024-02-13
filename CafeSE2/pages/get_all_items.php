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
$itemNo = isset($_GET['itemNo']) ? (int)$_GET['itemNo'] : 0;

// Sanitize the item number to prevent SQL injection
$itemNo = $conn->real_escape_string($itemNo);

// Fetch details of the specified item or all items from the database
$sql = ($itemNo > 0)
    ? "SELECT ItemNo, Img, ProductName, Description, Price FROM menu WHERE ItemNo = $itemNo"
    : "SELECT ItemNo, Img, ProductName, Description, Price FROM menu";

$result = $conn->query($sql);

if (!$result) {
    // Handle query error
    echo "Error executing query: " . $conn->error;
} elseif ($result->num_rows > 0) {
    if ($itemNo > 0) {
        // Fetch data as associative array for a specific item
        $itemDetails = $result->fetch_assoc();

        // Read the image file into a variable
        $imagePath = "../images/" . $itemDetails['Img'];
        $imageData = base64_encode(file_get_contents($imagePath));

        // Embed the image data into the JSON response
        $itemDetails['Img'] = 'data:image/jpeg;base64,' . $imageData;

        // Output the JSON response
        echo json_encode($itemDetails);
    } else {
        // Fetch data for all items and return as JSON array with base64-encoded images
        $itemsArray = array();
        while ($row = $result->fetch_assoc()) {
            // Read the image file into a variable
            $imagePath = "../images/" . $row['Img'];
            $imageData = base64_encode(file_get_contents($imagePath));

            // Embed the image data into the JSON response
            $row['Img'] = 'data:image/jpeg;base64,' . $imageData;

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

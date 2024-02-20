<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafe_siena";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$product_ID = isset($_GET['product_ID']) ? (int)$_GET['product_ID'] : 0;
$product_ID = $conn->real_escape_string($product_ID);

$sql = ($product_ID > 0)
    ? "SELECT product_ID, product_img, product_name, product_description, product_price, product_category FROM Product WHERE product_ID = $product_ID"
    : "SELECT product_ID, product_img, product_name, product_description, product_price, product_category FROM Product";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Error executing query: ' . $conn->error, 'sql' => $sql]);
} elseif ($result->num_rows > 0) {
    $itemsArray = array();

    while ($row = $result->fetch_assoc()) {
        // Adjust the image path based on your directory structure
        $row['product_img'] = "../images/" . $row['product_img'];

        if (file_exists($row['product_img'])) {
            $imageData = base64_encode(file_get_contents($row['product_img']));
            $row['product_img'] = 'data:image/jpeg;base64,' . $imageData;
            $itemsArray[] = $row;
        } else {
            echo json_encode(['error' => 'Image file not found: ' . $row['product_img']]);
            exit;
        }
    }

    echo json_encode($itemsArray);
} else {
    echo json_encode(['error' => 'No data available']);
}

$conn->close();
?>

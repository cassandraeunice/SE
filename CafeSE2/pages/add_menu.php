<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafesienadb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the file was uploaded successfully
if (isset($_FILES["product_img"]) && $_FILES["product_img"]["error"] == UPLOAD_ERR_OK) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    // Process the file upload
    $image = $_FILES["product_img"]["name"];
    $target_dir = "../images/";

    // Move the uploaded file to the desired directory
    $target_file = $target_dir . basename($image);
    if (move_uploaded_file($_FILES["product_img"]["tmp_name"], $target_file)) {
        $sql = $conn->prepare("INSERT INTO menu (product_name, product_img, product_description, product_price) VALUES (?, ?, ?, ?)");
        $sql->bind_param("sssi", $product_name, $image, $product_description, $product_price);

        if ($sql->execute() === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error executing query']);
        }

        $sql->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Error moving uploaded file']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Error uploading file']);
}

$conn->close();
?>

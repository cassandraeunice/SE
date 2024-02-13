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
if (isset($_FILES["itemImage"]) && $_FILES["itemImage"]["error"] == UPLOAD_ERR_OK) {
    $productName = $_POST['ProductName'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];

    // Process the file upload
    $image = $_FILES["itemImage"]["name"];
    $target_dir = "../images/";

    // Move the uploaded file to the desired directory
    $target_file = $target_dir . basename($image);
    if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
        $sql = $conn->prepare("INSERT INTO menu (ProductName, Img, Description, Price) VALUES (?, ?, ?, ?)");
        $sql->bind_param("sssi", $productName, $image, $description, $price);

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

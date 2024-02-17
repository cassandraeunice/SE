<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafesienadb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the item number from the URL
$product_ID = $_GET['product_ID'];

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_category = $_POST['product_category']; // Assuming the category dropdown has the name 'product_category'
    $product_subcategory = isset($_POST['product_subcategory']) ? $_POST['product_subcategory'] : '';

    // Validate and sanitize user inputs
    $product_name = filter_var($product_name, FILTER_SANITIZE_STRING);
    $product_description = filter_var($product_description, FILTER_SANITIZE_STRING);
    $product_price = filter_var($product_price, FILTER_VALIDATE_FLOAT); // Assuming Price is a float, adjust if it's an integer
    $product_category = filter_var($product_category, FILTER_SANITIZE_STRING);
    $product_subcategory = filter_var($product_subcategory, FILTER_SANITIZE_STRING);

    if ($product_price === false) {
        // Handle invalid price input
        echo json_encode(['error' => 'Invalid price']);
        exit();
    }

    // If subcategory is not empty, concatenate it with the category
    if (!empty($product_subcategory)) {
        $product_category .= ', ' . $product_subcategory;
    }

    // Check if an image is uploaded
    if (!empty($_FILES['product_img']['name'])) {
        $targetDir = "../images/";  // Change this to your desired upload directory
        $imageFileType = strtolower(pathinfo($_FILES["product_img"]["name"], PATHINFO_EXTENSION));
        $targetFile = $targetDir . uniqid() . '.' . $imageFileType;

        // Upload the new image
        if (move_uploaded_file($_FILES["product_img"]["tmp_name"], $targetFile)) {
            // Update the database with the new image and other details
            $updateQuery = "UPDATE menu SET product_name=?, product_description=?, product_price=?, product_img=?, product_category=? WHERE product_ID=?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('ssdssi', $product_name, $product_description, $product_price, $targetFile, $product_category, $product_ID);

            if ($updateStmt->execute()) {
                // Return success message
                echo json_encode(['success' => 'Item updated successfully']);
            } else {
                // Return error message
                echo json_encode(['error' => 'Failed to update item']);
            }

            $updateStmt->close();
        } else {
            // Return error message if image upload fails
            echo json_encode(['error' => 'Failed to upload image']);
        }
    } else {
        // Update the database without changing the image
        $updateQuery = "UPDATE menu SET product_name=?, product_description=?, product_price=?, product_category=? WHERE product_ID=?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('ssdsi', $product_name, $product_description, $product_price, $product_category, $product_ID);

        if ($updateStmt->execute()) {
            // Return success message
            echo json_encode(['success' => 'Item updated successfully']);
        } else {
            // Return error message
            echo json_encode(['error' => 'Failed to update item']);
        }

        $updateStmt->close();
    }
} else {
    // Fetch item details from the database
    $selectQuery = "SELECT * FROM menu WHERE product_ID = ?";
    $selectStmt = $conn->prepare($selectQuery);
    $selectStmt->bind_param('i', $product_ID);

    if ($selectStmt->execute()) {
        // Get result and fetch associative array
        $result = $selectStmt->get_result();
        
        if ($result->num_rows > 0) {
            $itemDetails = $result->fetch_assoc();

            // Return the item details as JSON
            echo json_encode($itemDetails);
        } else {
            // Handle case where no item is found
            echo json_encode(['error' => 'Item not found']);
        }

        // Close the result set
        $result->close();
    } else {
        // Handle errors if necessary
        echo json_encode(['error' => 'Failed to fetch item details']);
    }

    $selectStmt->close();
}

$conn->close();
?>

<?php
include '../connect.php';

// Fetch existing categories and subcategories from the database
$category_query = "SELECT * FROM Category";
$category_result = mysqli_query($con, $category_query);
$categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);

$subcategories_query = "SELECT * FROM Subcategory";
$subcategories_result = mysqli_query($con, $subcategories_query);
$subcategories = mysqli_fetch_all($subcategories_result, MYSQLI_ASSOC);

// Fetch the product details to be updated
$id = $_GET['product_id'];
$sql = "SELECT * FROM Product WHERE product_ID=$id";
$result = mysqli_query($con, $sql);
$product = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])){
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_category_ID = $_POST['product_category']; // Now fetching category ID instead of name
    $product_subcategory_ID = isset($_POST['product_subcategory']) ? $_POST['product_subcategory'] : null;

// Check if an image is uploaded
if ($_FILES['product_image']['name']) {
    $product_image = $_FILES['product_image']['name'];
    $target_dir = "../menu_images/";
    $target_file = $target_dir . basename($product_image);

    // Move the uploaded image
    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        // Construct the SQL query
        $sql = "UPDATE Product SET product_name='$product_name', category_ID=$product_category_ID, ";
        
        // Check if subcategory_ID is empty
        if ($product_subcategory_ID === null || $product_subcategory_ID === '') {
            // Exclude subcategory_ID from the query
            $sql .= "product_description='$product_description', product_image='$product_image', product_price=$product_price WHERE product_ID=$id";
        } else {
            // Include subcategory_ID in the query
            $sql .= "subcategory_ID=$product_subcategory_ID, product_description='$product_description', product_image='$product_image', product_price=$product_price WHERE product_ID=$id";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    // Construct the SQL query without considering image upload
    $sql = "UPDATE Product SET product_name='$product_name', category_ID=$product_category_ID, ";
    
    // Check if subcategory_ID is empty
    if ($product_subcategory_ID === null || $product_subcategory_ID === '') {
        // Exclude subcategory_ID from the query
        $sql .= "product_description='$product_description', product_price=$product_price WHERE product_ID=$id";
    } else {
        // Include subcategory_ID in the query
        $sql .= "subcategory_ID=$product_subcategory_ID, product_description='$product_description', product_price=$product_price WHERE product_ID=$id";
    }
}

    // Execute the SQL query
    $result = mysqli_query($con, $sql);
    if ($result) {
        header('location:../admin_menu.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Update Product</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Product Name</label>
                <input type="text" class="form-control" placeholder="Enter product name" name="product_name" value="<?php echo $product['product_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Product Category</label>
                <select class="form-select" name="product_category" required>
                    <option value="">Select Category</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['category_ID']; ?>" <?php if($category['category_ID'] == $product['category_ID']) echo "selected"; ?>><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Product Subcategory (Optional)</label>
                <select class="form-select" name="product_subcategory">
                    <option value="">Select Subcategory</option>
                    <?php foreach($subcategories as $subcategory): ?>
                        <option value="<?php echo $subcategory['subcategory_ID']; ?>" <?php if($subcategory['subcategory_ID'] == $product['subcategory_ID']) echo "selected"; ?>><?php echo $subcategory['subcategory_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Product Description</label>
                <textarea class="form-control" placeholder="Enter product description" name="product_description" required><?php echo $product['product_description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label>Product Image</label>
                <input type="file" class="form-control" name="product_image">
                <p class="text-muted">Leave blank to keep the existing image.</p>
            </div>
            <div class="mb-3">
                <label>Product Price</label>
                <input type="number" class="form-control" placeholder="Enter product price" name="product_price" value="<?php echo $product['product_price']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Update</button>
            <a href="../admin_menu.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>

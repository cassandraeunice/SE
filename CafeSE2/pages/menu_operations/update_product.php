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
    $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
    $product_description = mysqli_real_escape_string($con, $_POST['product_description']);
    $product_price = $_POST['product_price'];
    $product_category_ID = mysqli_real_escape_string($con, $_POST['product_category']);
    $product_subcategory_ID = isset($_POST['product_subcategory']) ? $_POST['product_subcategory'] : null;

    // Check if an image is uploaded
    if ($_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $product_image = $_FILES['product_image']['name'];
        $target_dir = "../../menu_images/";
        $target_file = $target_dir . basename($product_image);

        // Check image file validity
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<script>window.onload = function() { alert('File is not an image.'); }</script>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["product_image"]["size"] > 10 * 1024 * 1024) {
            echo "<script>window.onload = function() { alert('Sorry, your file is too large.'); }</script>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "<script>window.onload = function() { alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.'); }</script>";
            $uploadOk = 0;
        }

        // If file is valid, proceed with upload and database update
        if ($uploadOk == 1) {
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

                // Execute the SQL query
                $result = mysqli_query($con, $sql);
                if ($result) {
                    header('location:../admin_menu.php');
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($con);
                }
            } else {
                echo "<script>window.onload = function() { alert('Sorry, there was an error uploading your file.'); }</script>";
            }
        } else {
            echo "<script>window.onload = function() { alert('Sorry, your file was not uploaded.'); }</script>";
        }
    } else {
        // If no image is uploaded, proceed with database update without considering image upload
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

        // Execute the SQL query
        $result = mysqli_query($con, $sql);
        if ($result) {
            header('location:../admin_menu.php');
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Product</title>
    <link rel="stylesheet" href="../../css/update-product.css">
    <script>
        function confirmUpdate() {
            var result = confirm("Are you sure you want to update?");
            return result;
        }
    </script>
</head>

<body>
    <div class="container my-5">
    <a href="../admin_menu.php" class="btn btn-secondary"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#271300" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#271300"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path></g></svg></a>
        <h2>Update Product</h2>
        <form method="post" enctype="multipart/form-data" onsubmit="return confirmUpdate()">
            <div class="mb-3">
                <label>Product Name:</label><br></br>
                <input type="text" class="form-control" placeholder="Enter product name" name="product_name" value="<?php echo $product['product_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Product Category:</label><br></br>
                <select class="form-select" name="product_category" required>
                    <option value="">Select Category</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['category_ID']; ?>" <?php if($category['category_ID'] == $product['category_ID']) echo "selected"; ?>><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Product Subcategory (Optional):</label><br></br>
                <select class="form-select" name="product_subcategory">
                    <option value="">Select Subcategory</option>
                    <?php foreach($subcategories as $subcategory): ?>
                        <option value="<?php echo $subcategory['subcategory_ID']; ?>" <?php if($subcategory['subcategory_ID'] == $product['subcategory_ID']) echo "selected"; ?>><?php echo $subcategory['subcategory_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Product Description:</label>
                <textarea class="form-control" placeholder="Enter product description" name="product_description" required><?php echo $product['product_description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label>Product Image:</label><br></br>
                <input type="file" class="custom-file-input" name="product_image">
                <p class="text-muted">Leave blank to keep the existing image.</p>
            </div>
            <div class="mb-3">
                <label>Product Price:</label><br></br>
                <input type="number" class="form-control" placeholder="Enter product price" name="product_price" value="<?php echo $product['product_price']; ?>" required>
            </div>
            <div class="button-container">
            <button type="submit" class="btn btn-primary" name="submit">Update</button>
            </div>
        </form>
    </div>
</body>

</html>

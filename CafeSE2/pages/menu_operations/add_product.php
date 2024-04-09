<?php
include '../connect.php';

// Fetch existing categories from the database
$category_query = "SELECT * FROM Category";
$category_result = mysqli_query($con, $category_query);
$categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);

// Fetch all subcategories from the database
$subcategories_query = "SELECT * FROM Subcategory";
$subcategories_result = mysqli_query($con, $subcategories_query);
$subcategories = mysqli_fetch_all($subcategories_result, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    // Retrieve form data
    $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
    $product_description = mysqli_real_escape_string($con, $_POST['product_description']);
    $product_price = $_POST['product_price'];
    $product_category_ID = mysqli_real_escape_string($con, $_POST['product_category']);
    $product_subcategory_ID = isset($_POST['product_subcategory']) ? $_POST['product_subcategory'] : null;
    $product_image = $_FILES['product_image']['name'];
    $target_dir = "../../menu_images/";
    $target_file = $target_dir . basename($product_image);

    // Check image file validity
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if ($check !== false) {
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
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>window.onload = function() { alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.'); }</script>";
        $uploadOk = 0;
    }

    // If file is valid, proceed with upload and database insertion
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            if ($product_subcategory_ID === null || $product_subcategory_ID === '') {
                $sql = "INSERT INTO Product (admin_ID, product_name, category_ID, product_description, product_image, product_price) VALUES (1, '$product_name', $product_category_ID, '$product_description', '$product_image', $product_price)";
            } else {
                $sql = "INSERT INTO Product (admin_ID, product_name, category_ID, subcategory_ID, product_description, product_image, product_price) VALUES (1, '$product_name', $product_category_ID, $product_subcategory_ID, '$product_description', '$product_image', $product_price)";
            }

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
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
    <link rel="stylesheet" href="../../css/add-product.css">
</head>

<body>
    <div class="container my-5">
        <a href="../admin_menu.php"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#271300" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#271300">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path>
                </g>
            </svg></a>
        <h2>Add Product</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Product Name:</label><br></br>
                <input type="text" class="form-control" placeholder="Enter product name" name="product_name" required>
            </div>
            <div class="mb-3">
                <label>Product Category:</label><br></br>
                <select class="form-select" id="category" name="product_category" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['category_ID']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Product Subcategory (Optional):</label><br></br>
                <select class="form-select" id="subcategory" name="product_subcategory">
                    <option value="">Select Subcategory</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Product Description:</label><br></br>
                <textarea class="form-control" placeholder="Enter product description" name="product_description" required></textarea>
            </div>
            <div class="mb-3">
                <label>Product Image:</label><br></br>
                <input type="file" class="custom-file-input" name="product_image" required>
            </div>
            <div class="mb-3">
                <label>Product Price:</label><br></br>
                <input type="number" class="form-control" placeholder="Enter product price" name="product_price" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>
        </form>
    </div>

    <script>
        // JavaScript to dynamically load subcategories based on the selected category
        document.getElementById('category').addEventListener('change', function() {
            var categoryId = this.value;
            var subcategorySelect = document.getElementById('subcategory');
            subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

            <?php foreach ($subcategories as $subcategory) : ?>
                if (<?php echo $subcategory['category_ID']; ?> == categoryId) {
                    var option = document.createElement("option");
                    option.text = "<?php echo $subcategory['subcategory_name']; ?>";
                    option.value = "<?php echo $subcategory['subcategory_ID']; ?>";
                    subcategorySelect.add(option);
                }
            <?php endforeach; ?>
        });
    </script>
</body>

</html>
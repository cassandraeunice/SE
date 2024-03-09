<?php
include '../connect.php';

$id = $_GET['category_id'];

// Fetch category details based on the ID passed in the URL
$sql = "SELECT * FROM Category WHERE category_ID=$id";
$result = mysqli_query($con, $sql);
$category = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])){
    $category_name = $_POST['category_name'];

    // Check if the updated category name already exists in the database
    $check_query = "SELECT * FROM Category WHERE category_name = '$category_name' AND category_ID != $id";
    $check_result = mysqli_query($con, $check_query);
    $existing_category = mysqli_fetch_assoc($check_result);

    if($existing_category) {
        // Category name already exists in the database, display an error message
        echo "<script>window.onload = function() { alert('Category name already exists.'); }</script>";
    } else {
        // Update the category in the database
        $sql = "UPDATE Category SET category_name='$category_name' WHERE category_ID=$id";
        $result = mysqli_query($con, $sql);
        if ($result) {
            // If the category name is updated successfully, check if there's a new image uploaded
            if ($_FILES["category_image"]["size"] > 0) {
                $target_dir = "../../category_images/";
                $target_file = $target_dir . basename($_FILES["category_image"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Check file size
                if ($_FILES["category_image"]["size"] > 10 * 1024 * 1024) {
                    echo "<script>window.onload = function() { alert('Sorry, your file is too large.'); }</script>";
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    echo "<script>window.onload = function() { alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.'); }</script>";
                    $uploadOk = 0;
                }

                // If everything is ok, try to upload file
                if ($uploadOk == 1) {
                    if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
                        // Image uploaded successfully, update the category image in the database
                        $category_image = basename($_FILES["category_image"]["name"]);
                        $sql = "UPDATE Category SET category_image='$category_image' WHERE category_ID=$id";
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                            header('location:../admin_menu.php'); 
                        } else {
                            echo "Error: " . $sql . "<br>" . mysqli_error($con);
                        }
                    } else {
                        echo "<script>window.onload = function() { alert('Sorry, there was an error uploading your file.'); }</script>";
                    }
                }
            } else {
                header('location:../admin_menu.php'); 
            }
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
    <title>Update Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmUpdate() {
            var result = confirm("Updating this category will also update associated products and subcategories. Are you sure you want to proceed?");
            return result;
        }
    </script>
</head>

<body>
    <div class="container my-5">
        <h2>Update Category</h2>
        <form method="post" enctype="multipart/form-data" onsubmit="return confirmUpdate()">
            <div class="mb-3">
                <label>Category Name</label>
                <input type="text" class="form-control" placeholder="Enter category name" name="category_name" value="<?php echo $category['category_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Category Image</label>
                <input type="file" class="form-control" name="category_image">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="../admin_menu.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>

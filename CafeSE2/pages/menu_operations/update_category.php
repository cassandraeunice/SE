<?php
include '../connect.php';

$id = $_GET['category_id'];

// Fetch category details based on the ID passed in the URL
$sql = "SELECT * FROM Category WHERE category_ID=$id";
$result = mysqli_query($con, $sql);
$category = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])){
    $category_name = mysqli_real_escape_string($con, $_POST['category_name']);

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
    <link rel="stylesheet" href="../../css/update-category.css">
    <script>
        function confirmUpdate() {
            var result = confirm("Updating this category will also update associated products and subcategories. Are you sure you want to proceed?");
            return result;
        }
    </script>
</head>

<body>
    <div class="container my-5">
    <a href="../admin_menu.php"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#FFF3E2" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path></g></svg></a>
        <h2>Update Category</h2>
        <form method="post" enctype="multipart/form-data" onsubmit="return confirmUpdate()">
            <div class="mb-3">
                <label>Category Name:</label>
                <input type="text" class="form-control" placeholder="Enter category name" name="category_name" value="<?php echo $category['category_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Category Image:</label>
                <input type="file" class="custom-file-input" name="category_image">
            </div>
            <div class="button-container">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>

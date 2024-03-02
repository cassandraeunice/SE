<?php
include '../connect.php';

if(isset($_POST['submit'])){
    $category_name = $_POST['category_name'];

    // Check if the category name already exists
    $check_query = "SELECT * FROM Category WHERE category_name = '$category_name'";
    $check_result = mysqli_query($con, $check_query);
    $existing_category = mysqli_fetch_assoc($check_result);

    if($existing_category) {
        // Category name already exists, display an error message
        echo "Category name already exists.";
    } else {
        // Image upload
        $target_dir = "../../category_images/";
        $target_file = $target_dir . basename($_FILES["category_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["category_image"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["category_image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // If everything is ok, try to upload file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
                // Image uploaded successfully, proceed with insertion
                $category_image = basename( $_FILES["category_image"]["name"]);
                $sql = "INSERT INTO Category (category_name, category_image) VALUES ('$category_name', '$category_image')";
                $result = mysqli_query($con, $sql);
                if ($result) {
                    header('location:../admin_menu.php'); 
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($con);
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Category</title>
    <link rel="stylesheet" href="../../css/add-category.css">
</head>

<body>
    <div class="container">
        <h2>Add Category</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Category Name:</label><br>
                <input type="text" class="form-control" placeholder="Enter category name" name="category_name" required>
            </div>
            <div class="mb-3">
                <label>Category Image:</label><br>
                <input class="custom-file-input" type="file" name="category_image" required>
            </div>
            <div class="button-container"> 
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <a href="../admin_menu.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</body>

</html>

<?php
include '../connect.php';

// Fetch category details based on the ID passed in the URL
$id = $_GET['category_id'];
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
        echo "Category name already exists.";
    } else {
        // Update the category in the database
        $sql = "UPDATE Category SET category_name='$category_name' WHERE category_ID=$id";
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
    <title>Update Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Update Category</h2>
        <form method="post" enctype="multipart/form-data">
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

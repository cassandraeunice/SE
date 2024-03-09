<?php
include '../connect.php';

// Fetch subcategory details based on the ID passed in the URL
$id = $_GET['subcategory_id'];
$sql = "SELECT * FROM Subcategory WHERE subcategory_ID=$id";
$result = mysqli_query($con, $sql);
$subcategory = mysqli_fetch_assoc($result);

// Fetch existing categories from the database
$category_query = "SELECT * FROM Category";
$category_result = mysqli_query($con, $category_query);
$categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);

if(isset($_POST['submit'])){
    $subcategory_name = $_POST['subcategory_name'];
    $category_ID = $_POST['category_ID'];

    // Check if the updated subcategory name already exists in the database
    $check_query = "SELECT * FROM Subcategory WHERE subcategory_name = '$subcategory_name' AND category_ID = $category_ID AND subcategory_ID != $id";
    $check_result = mysqli_query($con, $check_query);
    $existing_subcategory = mysqli_fetch_assoc($check_result);

    if($existing_subcategory) {
        // Subcategory name already exists in the database for the selected category, display an error message
        echo "<script>window.onload = function() { alert('Subcategory name already exists for the selected category.'); }</script>";
    } else {
        // Update the subcategory in the database
        $sql = "UPDATE Subcategory SET subcategory_name='$subcategory_name', category_ID=$category_ID WHERE subcategory_ID=$id";
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
    <title>Update Subcategory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmUpdate() {
            var result = confirm("Updating this subcategory will also update associated products. Are you sure you want to proceed?");
            return result;
        }
    </script>
</head>

<body>
    <div class="container my-5">
        <h2>Update Subcategory</h2>
        <form method="post" onsubmit="return confirmUpdate()">
        <div class="mb-3">
                <label>Category</label>
                <select class="form-select" name="category_ID" required>
                    <option value="">Select Category</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['category_ID']; ?>" <?php if($category['category_ID'] == $subcategory['category_ID']) echo "selected"; ?>><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Subcategory Name</label>
                <input type="text" class="form-control" placeholder="Enter subcategory name" name="subcategory_name" value="<?php echo $subcategory['subcategory_name']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="../admin_menu.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>

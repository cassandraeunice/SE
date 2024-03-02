<?php
include '../connect.php';

// Fetch existing categories from the database
$category_query = "SELECT * FROM Category";
$category_result = mysqli_query($con, $category_query);
$categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    $subcategory_name = $_POST['subcategory_name'];
    $category_ID = $_POST['category_ID'];

    // Check if the subcategory name already exists for the selected category
    $check_query = "SELECT * FROM Subcategory WHERE subcategory_name = '$subcategory_name' AND category_ID = '$category_ID'";
    $check_result = mysqli_query($con, $check_query);
    $existing_subcategory = mysqli_fetch_assoc($check_result);

    if ($existing_subcategory) {
        // Subcategory name already exists for the selected category, display an error message
        echo "Subcategory name already exists for the selected category.";
    } else {
        // Subcategory name does not exist for the selected category, proceed with insertion
        $sql = "INSERT INTO Subcategory (subcategory_name, category_ID) VALUES ('$subcategory_name', '$category_ID')";
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
    <title>Add Subcategory</title>
    <link rel="stylesheet" href="../../css/add-subcategory.css">
</head>

<body>
    <div class="container my-5">
        <h2>Add Subcategory</h2>
        <form method="post">
            <div class="mb-3">
                <label>Category:</label>
                <select class="form-select" name="category_ID" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['category_ID']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Subcategory Name:</label>
                <input type="text" class="form-control" placeholder="Enter subcategory name" name="subcategory_name" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <a href="../admin_menu.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</body>

</html>
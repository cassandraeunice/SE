<?php
include '../connect.php';

// Fetch existing categories from the database
$category_query = "SELECT * FROM Category";
$category_result = mysqli_query($con, $category_query);
$categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    $subcategory_name = mysqli_real_escape_string($con, $_POST['subcategory_name']);
    $category_ID = $_POST['category_ID'];

    // Check if the subcategory name already exists for the selected category
    $check_query = "SELECT * FROM Subcategory WHERE subcategory_name = '$subcategory_name' AND category_ID = '$category_ID'";
    $check_result = mysqli_query($con, $check_query);
    $existing_subcategory = mysqli_fetch_assoc($check_result);

    if ($existing_subcategory) {
        // Subcategory name already exists for the selected category, display an error message
        echo "<script>window.onload = function() { alert('Subcategory name already exists for the selected category.'); }</script>";
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
    <a href="../admin_menu.php"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#FFF3E2" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path></g></svg></a>
        <h2>Add Subcategory</h2>
        <form method="post">
            <div class="mb-3">
                <label>Category:</label><br></br>
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
            </div>
        </form>
    </div>
</body>

</html>
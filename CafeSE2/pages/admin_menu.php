<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../css/dashboard-menu.css">
    
</head>

<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_menu.php">Menu Content</a></li>
            <li><a href="#">Home Content</a></li>
            <li><a href="admin_contact_us.php">Contact Us Record</a></li>
            <li><a href="admin_feedback_content.php">Feedback Content</a></li>
            <li><a href="admin_feedback_record.php">Feedback Record</a></li>
            <li><a href="admin_feedback_statistics.php">Feedback Statistics</a></li>
            <li><a href="#">About Content</a></li>
        </ul>
    </div>
    
    <div class="container">
        <h2>Menu Product</h2>
        <button class="btn btn-primary m-5"><a href="menu_operations/add_product.php" class="text-light">Add Product</a></button>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Image</th>
                        <th scope="col">Category</th>
                        <th scope="col">Subcategory</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.*, c.category_name, s.subcategory_name FROM Product p LEFT JOIN Category c ON p.category_ID = c.category_ID LEFT JOIN Subcategory s ON p.subcategory_ID = s.subcategory_ID";
                    $result = mysqli_query($con, $sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $product_id = $row['product_ID'];
                            $product_name = $row['product_name'];
                            $product_category = $row['category_name'];
                            $product_subcategory = $row['subcategory_name'];
                            $product_description = $row['product_description'];
                            $product_image = $row['product_image'];
                            $product_price = $row['product_price'];
                            echo '<tr>
                            <th scope="row">' . $product_id . '</th>
                            <td>' . $product_name . '</td>
                            <td><img src="../menu_images/' . $product_image . '" style="max-width: 100px; max-height: 100px;"></td>
                            <td>' . $product_category . '</td>
                            <td>' . $product_subcategory . '</td>
                            <td>' . $product_description . '</td>
                            <td>' . $product_price . '</td>
                            <td>
                            <button class="btn btn-primary"><a href="menu_operations/update_product.php?product_id=' . $product_id . '" class="text-light">Update</a></button>
                            <button class="btn btn-danger"><a href="menu_operations/delete_product.php?product_id=' . $product_id . '" class="text-light">Delete</a></button>
                            </td>
                        </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2>Menu Category</h2>
        <button class="btn btn-primary m-5"><a href="menu_operations/add_category.php" class="text-light">Add Category</a></button>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Category Image</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $category_query = "SELECT * FROM Category";
                    $category_result = mysqli_query($con, $category_query);
                    if ($category_result) {
                        while ($row = mysqli_fetch_assoc($category_result)) {
                            $category_id = $row['category_ID'];
                            $category_name = $row['category_name'];
                            $category_image = $row['category_image'];
                            echo '<tr>
                                <th scope="row">' . $category_id . '</th>
                                <td>' . $category_name . '</td>
                                <td><img src="../category_images/' . $category_image . '" style="max-width: 100px; max-height: 100px;"></td> <!-- Display category image -->
                                <td>
                                <button class="btn btn-primary"><a href="menu_operations/update_category.php?category_id=' . $category_id . '" class="text-light">Update</a></button>
                                <button class="btn btn-danger"><a href="menu_operations/delete_category.php?category_id=' . $category_id . '" class="text-light">Delete</a></button>
                                </td>
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <h2>Menu Subcategory</h2>
        <button class="btn btn-primary m-5"><a href="menu_operations/add_subcategory.php" class="text-light">Add Subcategory</a></button>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Subcategory Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $subcategory_query = "SELECT s.*, c.category_name FROM Subcategory s LEFT JOIN Category c ON s.category_ID = c.category_ID";
                    $subcategory_result = mysqli_query($con, $subcategory_query);
                    if ($subcategory_result) {
                        while ($row = mysqli_fetch_assoc($subcategory_result)) {
                            $subcategory_id = $row['subcategory_ID'];
                            $subcategory_name = $row['subcategory_name'];
                            $category_name = $row['category_name'];
                            echo '<tr>
                                <th scope="row">' . $subcategory_id . '</th>
                                <td>' . $subcategory_name . '</td>
                                <td>' . $category_name . '</td>
                                <td>
                                <button class="btn btn-primary"><a href="menu_operations/update_subcategory.php?subcategory_id=' . $subcategory_id . '" class="text-light">Update</a></button>
                                <button class="btn btn-danger"><a href="menu_operations/delete_subcategory.php?subcategory_id=' . $subcategory_id . '" class="text-light">Delete</a></button>
                                </td>
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>

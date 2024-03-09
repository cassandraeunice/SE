<?php
include 'connect.php';

// Number of records per page
$records_per_page = 2;

// Get the current page number
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../css/dashboard-menu.css">
    <script>
        function confirmDeleteProduct(productId) {
            var result = confirm("Are you sure you want to delete this product?");
            if (result) {
                window.location.href = 'menu_operations/delete_product.php?product_id=' + productId;
            }
        }

        function confirmDeleteCategory(categoryId) {
            var result = confirm("Are you sure you want to delete this category?");
            if (result) {
                window.location.href = 'menu_operations/delete_category.php?category_id=' + categoryId;
            }
        }

        function confirmDeleteSubcategory(subcategoryId) {
            var result = confirm("Are you sure you want to delete this subcategory?");
            if (result) {
                window.location.href = 'menu_operations/delete_subcategory.php?subcategory_id=' + subcategoryId;
            }
        }

        window.onload = function() {
            if (window.location.hash === '#product_error') {
                alert("Must delete categories that are under this category first");
                setTimeout(function() {
                    window.location.href = "admin_menu.php";
                }, 100);
            }
            if (window.location.hash === '#category_error') {
                alert("Must delete subcategories that are under this category first");
                setTimeout(function() {
                    window.location.href = "admin_menu.php";
                }, 100);
            }
        }
    </script>
    <style>
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 3px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
        }

        .pagination a.current {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .pagination a:hover {
            background-color: #f2f2f2;
        }

        .pagination .ellipsis {
            padding: 0 5px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_home.php">Home Content</a></li>
            <li><a href="admin_menu.php">Menu Content</a></li>
            <li><a href="admin_contact_us.php">Contact Us Record</a></li>
            <li><a href="admin_feedback_content.php">Feedback Content</a></li>
            <li><a href="admin_feedback_record.php">Feedback Record</a></li>
            <li><a href="admin_feedback_statistics.php">Feedback Statistics</a></li>
            <li><a href="admin_about_us.php">About Us Content</a></li>
            <li><a href="admin_account.php">Account</a></li>
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
                    $sql = "SELECT p.*, c.category_name, s.subcategory_name FROM Product p LEFT JOIN Category c ON p.category_ID = c.category_ID LEFT JOIN Subcategory s ON p.subcategory_ID = s.subcategory_ID LIMIT $offset, $records_per_page";
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
                            <button class="btn btn-danger" onclick="confirmDeleteProduct(' . $product_id . ')"><a class="text-light">Delete</a></button>
                            </td>
                        </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <?php
            // Get total number of records
            $sql = "SELECT COUNT(*) AS total FROM Product";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_records = $row['total'];

            // Calculate total number of pages
            $total_pages = ceil($total_records / $records_per_page);

            // Display ellipsis pagination links
            echo '<div class="pagination">';

            // Previous button
            if ($current_page > 1) {
                echo '<a href="admin_menu.php?page=' . ($current_page - 1) . '">Previous</a>';
            }

            // Page numbers
            for ($i = max(1, $current_page - 2); $i <= min($current_page + 2, $total_pages); $i++) {
                if ($i == $current_page) {
                    echo '<span class="current">' . $i . '</span>';
                } else {
                    echo '<a href="admin_menu.php?page=' . $i . '">' . $i . '</a>';
                }
            }

            // Ellipsis before the last page
            if ($current_page + 2 < $total_pages) {
                echo '<span class="ellipsis">...</span>';
            }

            // Last page (if not already included)
            if ($current_page + 2 < $total_pages) {
                echo '<a href="admin_menu.php?page=' . $total_pages . '">' . $total_pages . '</a>';
            }

            // Next button
            if ($current_page < $total_pages) {
                echo '<a href="admin_menu.php?page=' . ($current_page + 1) . '">Next</a>';
            }

            echo '</div>';
            ?>
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
                                <button class="btn btn-danger" onclick="confirmDeleteCategory(' . $category_id . ')"><a class="text-light">Delete</a></button>
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
                                <button class="btn btn-danger" onclick="confirmDeleteSubcategory(' . $subcategory_id . ')"><a class="text-light">Delete</a></button>
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
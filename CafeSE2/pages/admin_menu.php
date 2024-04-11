<?php

session_start(); // Start session to access session variables

// Check if the admin is logged in
if (!isset($_SESSION['admin_ID']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Check if the admin's login status is remembered via cookie
    if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
        // If not logged in and no remembered login status, redirect to login page
        header("Location: login.php");
        exit();
    }
}

include 'connect.php';

// Number of records per page
$records_per_page = 5;

// Get the current page number
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;

if (isset($_POST['homeBtn'])) {
    header("Location: home.php");
    exit();
}

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    // Check if the checkbox was checked or unchecked
    if (isset($_POST['product_popular'])) {
        $product_popular = 1; // Checkbox was checked
    } else {
        $product_popular = 0; // Checkbox was unchecked
    }

    // Count the number of products with product_popular_value equal to 1
    $count_query = "SELECT COUNT(*) AS total FROM Product WHERE product_popular_value = 1";
    $count_result = mysqli_query($con, $count_query);
    $count_row = mysqli_fetch_assoc($count_result);
    $current_popular_count = $count_row['total'];

    $displayAlert = false;

    // Check if the current count is less than the minimum required number
    if ($product_popular == 0 && $current_popular_count - 1 < 3) {
        $displayAlert = true;
        echo "<script>var displayAlert = true;</script>";
    } else {
        // Prepare the SQL query
        $sql = "UPDATE Product SET product_popular_value = ? WHERE product_ID = ?";
        $stmt = $con->prepare($sql);

        // Bind parameters and execute the query
        $stmt->bind_param("ii", $product_popular, $product_id);
        $stmt->execute();

        if ($count_result) {
            $current_page = isset($_POST['current_page']) ? $_POST['current_page'] : 1;
            header("Location: admin_menu.php?page=" . $current_page);
            exit();
        } else {
            die(mysqli_error($con));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../css/dashboard-menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                alert("There are products associated with this category. Ensure they are removed or reassigned before deleting this category.");
                setTimeout(function() {
                    window.location.href = "admin_menu.php";
                }, 50);
            }
            if (window.location.hash === '#product_error2') {
                alert("There are products associated with this subcategory. Ensure they are removed or reassigned before deleting this subcategory.");
                setTimeout(function() {
                    window.location.href = "admin_menu.php";
                }, 50);
            }
            if (window.location.hash === '#subcategory_error') {
                alert("There are subcategories associated with this category. Ensure they are removed or reassigned before deleting this category.");
                setTimeout(function() {
                    window.location.href = "admin_menu.php";
                }, 50);
            }
            if (window.location.hash === '#popular_error') {
                alert("Product cannot be deleted because it is marked as popular. Ensure they are removed before deleting this product.");
                setTimeout(function() {
                    window.location.href = "admin_menu.php";
                }, 50);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Check if the PHP flag is set to display an alert
            if (typeof displayAlert !== 'undefined' && displayAlert) {
                // Display the alert only if the PHP flag is true
                window.onload = function() {
                    alert('Atleast 3 popular products must be displayed.')
                };
            } else {
                // Your existing alert logic for JavaScript
                const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('click', function(e) {
                        var checkedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked').length;
                    });
                });
            }
        });
        
    </script>
</head>

<body>
    <div class="sidebar">
        <h2>CAFÉ SIENA</h2>
        <form method="post">
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
            <div class="homeBtn">
                <button type="submit" name="homeBtn"> Customer Dashboard</button>
            </div>
        </form>
    </div>

    <div class="container">
        <h2>Menu Product</h2>
        <button class="btn btn-primary m-5"><a href="menu_operations/add_product.php" class="text-light">Add
                Product</a></button>
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
                        <th scope="col">Popular</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.*, c.category_name, s.subcategory_name, p.product_popular_value FROM Product p LEFT JOIN Category c ON p.category_ID = c.category_ID LEFT JOIN Subcategory s ON p.subcategory_ID = s.subcategory_ID ORDER BY p.product_ID DESC LIMIT $offset, $records_per_page";
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
                            $product_popular_value = $row['product_popular_value'];
                    ?>
                            <tr>
                                <th scope="row"><?= $product_id ?></th>
                                <td><?= $product_name ?></td>
                                <td><img src="../menu_images/<?= $product_image ?>" style="max-width: 100px; max-height: 100px;"></td>
                                <td><?= $product_category ?></td>
                                <td><?= $product_subcategory ?></td>
                                <td><?= $product_description ?></td>
                                <td><?= $product_price ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                        <input type="hidden" name="current_page" value="<?= $current_page ?>">
                                        <input type="checkbox" name="product_popular" value="1" <?= $product_popular_value == 1 ? 'checked' : '' ?> onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td>
                                    <button class="btn-update"><a href="menu_operations/update_product.php?product_id=<?= $product_id ?>" class="text-light"><svg width="20px" height="20px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFF3E2">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                                </g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <title>edit [#FFF3E2]</title>
                                                    <desc>Created with Sketch.</desc>
                                                    <defs> </defs>
                                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g id="Dribbble-Light-Preview" transform="translate(-99.000000, -400.000000)" fill="#FFF3E2">
                                                            <g id="icons" transform="translate(56.000000, 160.000000)">
                                                                <path d="M61.9,258.010643 L45.1,258.010643 L45.1,242.095788 L53.5,242.095788 L53.5,240.106431 L43,240.106431 L43,260 L64,260 L64,250.053215 L61.9,250.053215 L61.9,258.010643 Z M49.3,249.949769 L59.63095,240 L64,244.114985 L53.3341,254.031929 L49.3,254.031929 L49.3,249.949769 Z" id="edit-[#FFF3E2]"> </path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg></a></button>
                                    <button class="btn-delete" onclick="confirmDeleteProduct(<?= $product_id ?>)"><a class="text-light"><svg width="20px" height="20px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#FFF3E2">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                                </g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path fill="#FFF3E2" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64h256zm64 0h192v-64H416v64zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32H192zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32zm192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32z">
                                                    </path>
                                                </g>
                                            </svg></a></button>
                                </td>
                            </tr>
                    <?php
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
        <button class="btn btn-primary m-5"><a href="menu_operations/add_category.php" class="text-light">Add
                Category</a></button>
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
                    $category_query = "SELECT * FROM Category ORDER BY category_ID DESC";
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
                                <button class="btn-update"><a href="menu_operations/update_category.php?category_id=' . $category_id . '" class="text-light"><svg width="20px" height="20px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>edit [#FFF3E2]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-99.000000, -400.000000)" fill="#FFF3E2"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M61.9,258.010643 L45.1,258.010643 L45.1,242.095788 L53.5,242.095788 L53.5,240.106431 L43,240.106431 L43,260 L64,260 L64,250.053215 L61.9,250.053215 L61.9,258.010643 Z M49.3,249.949769 L59.63095,240 L64,244.114985 L53.3341,254.031929 L49.3,254.031929 L49.3,249.949769 Z" id="edit-[#FFF3E2]"> </path> </g> </g> </g> </g></svg></a></button>
                                <button class="btn-delete" onclick="confirmDeleteCategory(' . $category_id . ')"><a class="text-light"><svg width="20px" height="20px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#FFF3E2" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64h256zm64 0h192v-64H416v64zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32H192zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32zm192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32z"></path></g></svg></a></button>
                                </td>
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <h2>Menu Subcategory</h2>
        <button class="btn btn-primary m-5"><a href="menu_operations/add_subcategory.php" class="text-light">Add
                Subcategory</a></button>
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
                    $subcategory_query = "SELECT s.*, c.category_name FROM Subcategory s LEFT JOIN Category c ON s.category_ID = c.category_ID ORDER BY s.subcategory_ID DESC";
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
                                <button class="btn-update"><a href="menu_operations/update_subcategory.php?subcategory_id=' . $subcategory_id . '" class="text-light"><svg width="20px" height="20px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>edit [#FFF3E2]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-99.000000, -400.000000)" fill="#FFF3E2"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M61.9,258.010643 L45.1,258.010643 L45.1,242.095788 L53.5,242.095788 L53.5,240.106431 L43,240.106431 L43,260 L64,260 L64,250.053215 L61.9,250.053215 L61.9,258.010643 Z M49.3,249.949769 L59.63095,240 L64,244.114985 L53.3341,254.031929 L49.3,254.031929 L49.3,249.949769 Z" id="edit-[#FFF3E2]"> </path> </g> </g> </g> </g></svg></a></button>
                                <button class="btn-delete" onclick="confirmDeleteSubcategory(' . $subcategory_id . ')"><a class="text-light"><svg width="20px" height="20px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#FFF3E2" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64h256zm64 0h192v-64H416v64zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32H192zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32zm192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32z"></path></g></svg></a></button>
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


<!-- <td><input type="checkbox" name="product_popular" value="" <?php echo $product_popular_value == 1 ? 'checked' : ''; ?> disabled></td> -->
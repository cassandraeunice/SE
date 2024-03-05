<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-feedback-content.css"> <!-- fix this -->
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
        <h2>Carousel Images</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Content ID</th>
                        <th scope="col">Content Image</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $content_query = "SELECT * FROM Content WHERE content_ID IN (1, 2, 3)";
                    $content_result = mysqli_query($con, $content_query);
                    if ($content_result) {
                        while ($row = mysqli_fetch_assoc($content_result)) {
                            $content_id = $row['content_ID'];
                            $content_image = $row['content_image'];
                            echo '<tr>
                            <th scope="row">' . $content_id . '</th>
                            <td><img src="../content_images/' . $content_image . '" style="max-width: 100px; max-height: 100px;"></td>
                            <td>
                                <button class="btn btn-primary"><a href="home_operations/update_content_image.php?content_id=' . $content_id . '" class="text-light">Update</a></button>
                            </td>
                        </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <h2>About Us Section</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Content ID</th>
                        <th scope="col">Content Image</th>
                        <th scope="col">Content Text</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $content_query = "SELECT * FROM Content WHERE content_ID IN (4)";
                    $content_result = mysqli_query($con, $content_query);
                    if ($content_result) {
                        while ($row = mysqli_fetch_assoc($content_result)) {
                            $content_id = $row['content_ID'];
                            $content_image = $row['content_image'];
                            $content_text = $row['content_text'];
                            echo '<tr>
                        <th scope="row">' . $content_id . '</th>
                        <td><img src="../content_images/' . $content_image . '" style="max-width: 100px; max-height: 100px;"></td>
                        <td>' . $content_text . '</td>
                        <td>
                            <button class="btn btn-primary"><a href="home_operations/update_content.php?content_id=' . $content_id . '" class="text-light">Update</a></button>
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
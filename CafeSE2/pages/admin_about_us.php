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

if(isset($_POST['homeBtn'])){
    header("Location: home.php"); // Redirect to admin_home.php
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-about-us-content.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        <h2>About Us Section</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Content Name</th>
                        <th scope="col">Content Image</th>
                        <th scope="col">Content Text</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $content_query = "SELECT * FROM Content WHERE content_ID IN (5)";
                    $content_result = mysqli_query($con, $content_query);
                    if ($content_result) {
                        while ($row = mysqli_fetch_assoc($content_result)) {
                            $content_id = $row['content_ID'];
                            $content_image = $row['content_image'];
                            $content_text = $row['content_text'];
                            $content_name = "About Us Image and Text";
                        echo '<tr>
                        <th scope="row">' . $content_name . '</th>
                        <td><img src="../content_images/' . $content_image . '" style="max-width: 100px; max-height: 100px;"></td>
                        <td>' . nl2br($content_text) . '</td>
                        <td>
                            <button class="btn-update"><a href="about_us_operations/update_content.php?content_id=' . $content_id . '" class="text-light"><svg width="20px" height="20px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>edit [#FFF3E2]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-99.000000, -400.000000)" fill="#FFF3E2"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M61.9,258.010643 L45.1,258.010643 L45.1,242.095788 L53.5,242.095788 L53.5,240.106431 L43,240.106431 L43,260 L64,260 L64,250.053215 L61.9,250.053215 L61.9,258.010643 Z M49.3,249.949769 L59.63095,240 L64,244.114985 L53.3341,254.031929 L49.3,254.031929 L49.3,249.949769 Z" id="edit-[#FFF3E2]"> </path> </g> </g> </g> </g></svg></a></button>
                        </td>
                    </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <h2>Visit Us Section</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Content Name</th>
                        <th scope="col">Content Text</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $content_query = "SELECT * FROM Content WHERE content_ID IN (6, 7, 8, 9)";
                    $content_result = mysqli_query($con, $content_query);
                    if ($content_result) {
                        while ($row = mysqli_fetch_assoc($content_result)) {
                            $content_id = $row['content_ID'];
                            $content_text = $row['content_text'];
                            $content_name = "";
                            if ($content_id == 6) {
                                $content_name = "Location";
                            } elseif ($content_id == 7) {
                                $content_name = "Open hours";
                            } elseif ($content_id == 8) {
                                $content_name = "Contact number";
                            } elseif ($content_id == 9) {
                                $content_name = "Email";
                            }
                            echo '<tr>
                            <th scope="row">' . $content_name . '</th>
                            <td>' . $content_text . '</td>
                            <td>
                                <button class="btn-update"><a href="about_us_operations/update_content_text.php?content_id=' . $content_id . '" class="text-light"><svg width="20px" height="20px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>edit [#FFF3E2]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-99.000000, -400.000000)" fill="#FFF3E2"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M61.9,258.010643 L45.1,258.010643 L45.1,242.095788 L53.5,242.095788 L53.5,240.106431 L43,240.106431 L43,260 L64,260 L64,250.053215 L61.9,250.053215 L61.9,258.010643 Z M49.3,249.949769 L59.63095,240 L64,244.114985 L53.3341,254.031929 L49.3,254.031929 L49.3,249.949769 Z" id="edit-[#FFF3E2]"> </path> </g> </g> </g> </g></svg></a></button>
                            </td>
                        </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <h2>Socials</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Social Media</th>
                        <th scope="col">Social Media Link</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $content_query = "SELECT * FROM Content WHERE content_ID IN (10, 11)";
                    $content_result = mysqli_query($con, $content_query);
                    if ($content_result) {
                        while ($row = mysqli_fetch_assoc($content_result)) {
                            $content_id = $row['content_ID'];
                            $content_text = $row['content_text'];
                            $social_media_name = "";
                            if ($content_id == 10) {
                                $social_media_name = "Facebook";
                            } elseif ($content_id == 11) {
                                $social_media_name = "Instagram";
                            }
                            echo '<tr>
                            <th scope="row">' . $social_media_name . '</th>
                            <td>' . $content_text . '</td>
                            <td>
                                <button class="btn-update"><a href="about_us_operations/update_content_text.php?content_id=' . $content_id . '" class="text-light"><svg width="20px" height="20px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>edit [#FFF3E2]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-99.000000, -400.000000)" fill="#FFF3E2"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M61.9,258.010643 L45.1,258.010643 L45.1,242.095788 L53.5,242.095788 L53.5,240.106431 L43,240.106431 L43,260 L64,260 L64,250.053215 L61.9,250.053215 L61.9,258.010643 Z M49.3,249.949769 L59.63095,240 L64,244.114985 L53.3341,254.031929 L49.3,254.031929 L49.3,249.949769 Z" id="edit-[#FFF3E2]"> </path> </g> </g> </g> </g></svg></a></button>
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
<?php

session_start(); // Start session to access session variables

// Check if the admin is logged in
if (!isset($_SESSION['admin_ID']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Check if the admin's login status is remembered via cookie
    if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
        // If not logged in and no remembered login status, redirect to login page
        header("Location: ../login.php");
        exit();
    }
}

include '../connect.php';

// Fetch content details based on the ID passed in the URL
$id = $_GET['content_id'];
$sql = "SELECT * FROM Content WHERE content_ID=$id";
$result = mysqli_query($con, $sql);
$content = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    // Handle file upload for the new content image
    $content_image = $_FILES['content_image']['name'];
    $temp_image = $_FILES['content_image']['tmp_name'];

    // Get the file extension
    $file_extension = strtolower(pathinfo($content_image, PATHINFO_EXTENSION));

    // Array of allowed extensions
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

    // Check if the uploaded file has an allowed extension
    if (!in_array($file_extension, $allowed_extensions)) {
        echo "<script>window.onload = function() { alert('File is not an image.'); }</script>";
    } else {
        // Move the uploaded file
        move_uploaded_file($temp_image, '../content_images/' . $content_image);

        // Update the content image in the database
        $sql = "UPDATE Content SET content_image='$content_image' WHERE content_ID=$id";
        $result = mysqli_query($con, $sql);
        if ($result) {
            header('location:../admin_home.php');
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
    <title>Update Image</title>
    <link rel="stylesheet" href="../css/update-content-image.css">
    <script>
    function confirmUpdate() {
        var result = confirm("Are you sure you want to update?");
        return result;
    }

    function validateImage() {
        var input = document.querySelector('input[type="file"]');
        var file = input.files[0];

        var reader = new FileReader();

        reader.readAsDataURL(file);

        // Function to execute when file reading is complete
        reader.onload = function() {
            var img = new Image();
            img.src = reader.result;

            img.onload = function() {
                var aspectRatio = img.width / img.height;
                var lowerBound = 21 / 9 * 0.95; // Lower bound with 5% tolerance
                var upperBound = 21 / 9 * 1.05; // Upper bound with 5% tolerance

                if (aspectRatio < lowerBound || aspectRatio > upperBound) {
                    alert("Please upload an image with a 21:9 aspect ratio.");
                    input.value = '';
                }
            };
        };
    }
</script>
</head>

<body>
    <div class="container my-5">
        <a href="../admin_home.php" class="btn btn-secondary"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#271300" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#271300">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path>
                </g>
            </svg></a>
        <h2>Update Image</h2>
        <form method="post" enctype="multipart/form-data" onsubmit="return confirmUpdate()">
            <div class="mb-3">
                <input type="file" class="custom-file-input" name="content_image" onchange="validateImage()" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>
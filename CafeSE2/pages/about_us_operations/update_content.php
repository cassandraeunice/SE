<?php
include '../connect.php';

// Fetch content details based on the ID passed in the URL
$id = $_GET['content_id'];
$sql = "SELECT * FROM Content WHERE content_ID=$id";
$result = mysqli_query($con, $sql);
$content = mysqli_fetch_assoc($result);

// Store the existing image value
$existing_image = $content['content_image'];

if(isset($_POST['submit'])){
    // Get content text from the form
    $content_text = $_POST['content_text'];

    // Handle file upload for the new content image
    if(isset($_FILES['content_image']['name']) && $_FILES['content_image']['name'] !== '') {
        $content_image = $_FILES['content_image']['name'];
        $temp_image = $_FILES['content_image']['tmp_name'];
        move_uploaded_file($temp_image, '../../content_images/'.$content_image);
    } else {
        // If no new image is provided, retain the existing image value
        $content_image = $existing_image;
    }

    // Update the content text and image in the database
    $sql = "UPDATE Content SET content_text='$content_text', content_image='$content_image' WHERE content_ID=$id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        header('location:../admin_about_us.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Content</title>
    <link rel="stylesheet" href="../../css/update-content.css">
    <script>
        function confirmUpdate() {
            var result = confirm("Are you sure you want to update?");
            return result;
        }
    </script>
</head>

<body>
    <div class="container my-5">
    <a href="../admin_about_us.php" class="btn btn-secondary"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#FFF3E2" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path></g></svg></a>
        <h2>Update Content</h2>
        <form method="post" enctype="multipart/form-data" onsubmit="confirmUpdate()">
            <div class="mb-3">
                <label>Content Text</label>
                <input type="text" class="form-control" name="content_text" value="<?php echo $content['content_text']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Content Image</label>
                <input type="file" class="custom-file-input" name="content_image">
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>

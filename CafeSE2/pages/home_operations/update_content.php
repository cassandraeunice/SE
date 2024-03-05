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
        move_uploaded_file($temp_image, '../content_images/'.$content_image);
    } else {
        // If no new image is provided, retain the existing image value
        $content_image = $existing_image;
    }

    // Update the content text and image in the database
    $sql = "UPDATE Content SET content_text='$content_text', content_image='$content_image' WHERE content_ID=$id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        header('location:../admin_home.php');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Update Content</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Content Text</label>
                <input type="text" class="form-control" name="content_text" value="<?php echo $content['content_text']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Content Image</label>
                <input type="file" class="form-control" name="content_image">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="../admin_home.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>

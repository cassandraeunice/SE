<?php
include '../connect.php';

// Fetch content details based on the ID passed in the URL
$id = $_GET['content_id'];
$sql = "SELECT * FROM Content WHERE content_ID=$id";
$result = mysqli_query($con, $sql);
$content = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])){
    // Handle file upload for the new content image
    $content_image = $_FILES['content_image']['name'];
    $temp_image = $_FILES['content_image']['tmp_name'];
    move_uploaded_file($temp_image, '../../content_images/'.$content_image);

    // Update the content image in the database
    $sql = "UPDATE Content SET content_image='$content_image' WHERE content_ID=$id";
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
    <title>Update Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmUpdate() {
            var result = confirm("Are you sure you want to update?");
            return result;
        }
    </script>
</head>

<body>
    <div class="container my-5">
        <h2>Update Image</h2>
        <form method="post" enctype="multipart/form-data" onsubmit="return confirmUpdate()">
            <div class="mb-3">
                <input type="file" class="form-control" name="content_image" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="../admin_home.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>

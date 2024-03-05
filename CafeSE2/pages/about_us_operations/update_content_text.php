<?php
include '../connect.php';

// Fetch content details based on the ID passed in the URL
$id = $_GET['content_id'];
$sql = "SELECT * FROM Content WHERE content_ID=$id";
$result = mysqli_query($con, $sql);
$content = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])){
    // Get content text from the form
    $content_text = $_POST['content_text'];

    // Update the content text in the database
    $sql = "UPDATE Content SET content_text='$content_text' WHERE content_ID=$id";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Update Content</h2>
        <form method="post">
            <div class="mb-3">
                <label>Content Text</label>
                <input type="text" class="form-control" name="content_text" value="<?php echo $content['content_text']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="../admin_about_us.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>

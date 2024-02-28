<?php
include '../connect.php';

// Fetch section details based on the ID passed in the URL
$id = $_GET['section_id'];
$sql = "SELECT * FROM Section WHERE section_ID=$id";
$result = mysqli_query($con, $sql);
$section = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])){
    $section_name = $_POST['section_name'];

    // Check if the updated section name already exists in the database
    $check_query = "SELECT * FROM Section WHERE section_name = '$section_name' AND section_ID != $id";
    $check_result = mysqli_query($con, $check_query);
    $existing_section = mysqli_fetch_assoc($check_result);

    if($existing_section) {
        // Section name already exists in the database, display an error message
        echo "Section name already exists.";
    } else {
        // Update the section in the database
        $sql = "UPDATE Section SET section_name='$section_name' WHERE section_ID=$id";
        $result = mysqli_query($con, $sql);
        if ($result) {
            header('location:../admin_feedback_content.php'); 
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
    <title>Update Section</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Update Section</h2>
        <form method="post">
            <div class="mb-3">
                <label>Section Name</label>
                <input type="text" class="form-control" placeholder="Enter section name" name="section_name" value="<?php echo $section['section_name']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="../admin_feedback_content.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>

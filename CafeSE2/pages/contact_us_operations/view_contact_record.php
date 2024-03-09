<?php
include '../connect.php';

// Check if contact_id is provided in the URL
if (isset($_GET['contact_id'])) {
    $contact_id = $_GET['contact_id'];

    // Fetch contact details
    $contact_query = "SELECT * FROM ContactUs WHERE contact_id = $contact_id";
    $contact_result = mysqli_query($con, $contact_query);
    $contact_row = mysqli_fetch_assoc($contact_result);

    // Determine the status
    $status = $contact_row['contact_respond_value'] ? "Responded" : "Not Responded";

    // Check if the "Change Status" button is clicked
    if (isset($_POST['change_status'])) {
        // Toggle responded status
        $new_status = $contact_row['contact_respond_value'] ? 0 : 1;

        // Update responded status
        $update_query = "UPDATE ContactUs SET contact_respond_value = $new_status WHERE contact_id = $contact_id";
        $update_result = mysqli_query($con, $update_query);

        // Refresh the page to reflect the updated status
        header("Location: view_contact_record.php?contact_id=$contact_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Contact Us Details</h2>
        <table class="table">
            <tbody>
                <tr>
                    <th scope="row">Contact ID</th>
                    <td><?php echo $contact_row['contact_id']; ?></td>
                </tr>
                <tr>
                    <th scope="row">First Name</th>
                    <td><?php echo $contact_row['contact_first_name']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Last Name</th>
                    <td><?php echo $contact_row['contact_last_name']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td><?php echo $contact_row['contact_email']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Subject</th>
                    <td><?php echo $contact_row['contact_subject']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Message</th>
                    <td><?php echo $contact_row['contact_message']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Timestamp</th>
                    <td><?php echo $contact_row['contact_timestamp']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Status</th>
                    <td><?php echo $status; ?></td>
                </tr>
            </tbody>
        </table>
        <form method="post">
            <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
            <button type="submit" class="btn btn-primary" name="change_status">Change Status</button>
        </form>
        <a href="../admin_contact_us.php" class="btn btn-primary">Back</a>

    </div>
</body>

</html>
<?php
include '../connect.php';

// Check if contact_id is provided in the URL
if(isset($_GET['contact_id'])) {
    $contact_id = $_GET['contact_id'];
    
    // Fetch contact details
    $contact_query = "SELECT * FROM ContactUs WHERE contact_id = $contact_id";
    $contact_result = mysqli_query($con, $contact_query);
    $contact_row = mysqli_fetch_assoc($contact_result);

    // Determine the status
    $status = $contact_row['contact_respond_value'] ? "Responded" : "Not Responded";
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

        <a href="../admin_contact_us.php" class="btn btn-primary">Back to Contact Us Record</a>
    </div>
</body>

</html>

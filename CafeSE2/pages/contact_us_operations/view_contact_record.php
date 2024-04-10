<?php
include '../connect.php';

// Check if contact_id is provided in the URL
if (isset($_GET['contact_ID'])) {
    $contact_id = $_GET['contact_ID'];

    // Fetch contact details
    $contact_query = "SELECT * FROM ContactUs WHERE contact_ID = $contact_id";
    $contact_result = mysqli_query($con, $contact_query);
    $contact_row = mysqli_fetch_assoc($contact_result);

    // Determine the status
    $status = $contact_row['contact_respond_value'] ? "Responded" : "Not Responded";

    // Check if the "Change Status" button is clicked
    if (isset($_POST['change_status'])) {
        // Toggle responded status
        $new_status = $contact_row['contact_respond_value'] ? 0 : 1;

        // Update responded status
        $update_query = "UPDATE ContactUs SET contact_respond_value = $new_status WHERE contact_ID = $contact_id";
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
    <link rel="stylesheet" href="../../css/view-contact-record.css">
</head>

<body>
    <div class="container">
        <a href="../admin_contact_us.php" class="btn btn-primary"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#271300" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#271300"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path></g></svg></a>
        
        <h2>View Contact Us</h2>
        <h3>Contact Details</h3>
        <hr></hr><br></br>
        <div class="group1">
            <div class="contactID">
                <label>Contact ID:</label><br></br>
                <input type="text" value="<?php echo $contact_row['contact_ID']; ?>" disabled>
            </div>
            <div class="timestamp">
                <label>Timestamp:</label><br></br>
                <input type="text" value="<?php echo $contact_row['contact_timestamp']; ?>" disabled>
            </div>
        </div>
        <div class="group2">
            <div class="firstName">
                <label>First Name:</label><br></br>
                <input type="text" value="<?php echo $contact_row['contact_first_name']; ?>" disabled>
            </div>
            <div class="lastName">
                <label>Last Name:</label><br></br>
                <input type="text" value="<?php echo $contact_row['contact_last_name']; ?>" disabled>
            </div>
        </div>
        <div class="group3">
            <div class="email">
                <label>Email:</label><br></br>
                <input type="text" value="<?php echo $contact_row['contact_email']; ?>" disabled>
            </div>
            <div class="status">
                <label>Status:</label><br></br>
                <input type="text" value="<?php echo $status; ?>" disabled>
                <form method="post">
                <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>"><br></br>
                <div class="btn-change-container">
                    <button type="submit" class="btn-change" name="change_status">Change Status</button>
                </div>
            </form>
            </div>
        </div>

        <h3>Inquiry</h3>
        <hr></hr><br></br>
        <div class="subject">
            <label>Subject:</label>
            <input id="subjInput" type="text" value="<?php echo $contact_row['contact_subject']; ?>" disabled >
        </div>
        <div class="message">
            <label>Message:</label>
            <textarea disabled><?php echo $contact_row['contact_message']; ?></textarea>
        </div>
    </div>
</body>
</html>
<?php
// Define variables and initialize with empty values
$firstName = $lastName = $email = $subject = $message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    include 'connect.php';

    // Get admin ID
    $admin_ID = 1;

    $sql = "INSERT INTO ContactUs (admin_ID, contact_first_name, contact_last_name, contact_email, contact_subject, contact_message) 
            VALUES ('$admin_ID', '$firstName', '$lastName', '$email', '$subject', '$message')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        header('location:contact_us.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }

    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/contact-us.css">
</head>

<body>

    <div class="container">
        <h2>Contact Us</h2>
        <p class="welcome-message">Welcome to our Contact Us page! We're here to assist you—feel free to drop us a message anytime.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="firstName">First Name:</label><br></br>
                <input type="text" class="form-control" id="firstName" name="firstName" required value="<?php echo $firstName; ?>" placeholder="Enter your first name"><br></br>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label><br></br>
                <input type="text" class="form-control" id="lastName" name="lastName" required value="<?php echo $lastName; ?>" placeholder="Enter your last name"><br></br>
            </div>
            <div class="form-group">
                <label for="email">Email:</label><br></br>
                <input type="email" class="form-control" id="email" name="email" required value="<?php echo $email; ?>" placeholder="Enter your email"><br></br>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label><br></br>
                <input type="text" class="form-control" id="subject" name="subject" required value="<?php echo $subject; ?>" placeholder="Enter subject"><br></br>
            </div>
            <div class="form-group">
                <label for="message">Message:</label><br></br>
                <textarea class="form-control" id="message" name="message" rows="5" required><?php echo $message; ?></textarea><br></br>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            
        </form>
    </div>

</body>

</html>

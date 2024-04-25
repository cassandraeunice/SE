<?php
include 'connect.php';

// Fetch content details for content_IDs
$content_query = "SELECT * FROM Content WHERE content_ID IN (10, 11)";
$content_result = mysqli_query($con, $content_query);

// Initialize variables to store content_text for content_IDs
$content_texts = [];

// Fetch content_text and content_image for content_IDs
while ($row = mysqli_fetch_assoc($content_result)) {
    $content_texts[$row['content_ID']] = $row['content_text'];
}

// Define variables and initialize with empty values
$firstName = $lastName = $email = $subject = $message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($con, $_POST['lastName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $subject = mysqli_real_escape_string($con, $_POST['subject']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // Get admin ID
    $admin_ID = 1;

    $sql = "INSERT INTO ContactUs (admin_ID, contact_first_name, contact_last_name, contact_email, contact_subject, contact_message) 
            VALUES ('$admin_ID', '$firstName', '$lastName', '$email', '$subject', '$message')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        header('location:contact_us_thank_you.html');
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Contact Us</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/contact-us.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> <!-- Montserrat font -->

</head>

<body>

    <!-- NavBar -->
    <header class="header-nav">

        <a class="logo-nav">CAFÉ SIENA</a>

        <input type="checkbox" id="check">
        <label for="check" class="icons">
            <i class="bx bx-menu" id="menu-icon"></i>
            <i class="bx bx-x" id="close-icon"></i>
        </label>

        <nav class="navbar-links">
            <a href="home.php" style="--i:0;">Home</a>
            <a href="menu.php" style="--i:1;">Menu</a>
            <a href="contact_us.php" class="active" style="--i:2;">Contact Us</a>
            <a href="about.php" style="--i:3;">About</a>
        </nav>

    </header>

    <section>

        <div class="contact-top-container">

            <div class="contact-message">
                <p class="contact-text">Contact Us</p>
                <p class="home-contact">Do you have any questions? We would be glad to help you!</span></p>
            </div>

        </div>

    </section>



    <div class="contact-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="form-group">
            <p class="req-info">REQUEST INFORMATION</p>
                <div class="name-group">
                    <div class="firstName-input">
                        <label for="firstName">First Name:</label><br>
                        <input type="text" id="firstName" name="firstName" placeholder="Enter your first name">
                    </div>
                    <div class="lastName-input">
                        <label for="lastName">Last Name:</label><br>
                        <input type="text" id="lastName" name="lastName" placeholder="Enter your last name">
                    </div>
                </div>
            </div>
            <div class="form-group">
            <div class="email-input">
                <label for="email">Email:</label><br></br>
                <input type="email" class="form-control" id="email" name="email" required value="<?php echo $email; ?>" placeholder="Enter your email"><br></br>
            </div>
            <div class="inquiry-info">
                <p class="inquiry">INQUIRY</p>
                <p class="inquiry-text">We’d love to hear from you. Drop us a line if you have any questions.</p>
            </div>
            <div class="subj-input">
            <label for="subject">Subject:</label><br>
            <select id="subject" name="subject" required><br></br>
                <option value="">Select a subject</option>
                <option value="General Inquiry">General Inquiry</option>
                <option value="Reservation">Reservation</option>
                <option value="Intimate Events">Intimate Events</option>
                <option value="Catering">Catering</option>
                <option value="Packed Meals">Packed Meals</option>
            </select><br><br>
        </div>
            <div class="message-input">
                <label for="message">Message:</label><br></br>
                <textarea class="form-control" id="message" name="message" rows="5" required><?php echo $message; ?></textarea><br></br>
            </div>
        </div>
        <div class="button-container">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
    <div class="contact-information">
            <p>0917 588 6554</p>
            <a href="mailto:cafesienaph@gmail.com">cafesienaph@gmail.com</a>
        </div>


    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <p class="footer-logo">CAFÉ SIENA</p>
            </div>
            <div class="footer-content">
                <ul class="social-icons">
                    <!-- Dynamic hrefs for social media icons -->
                    <li><a href="<?php echo $content_texts[10]; ?>"><i class='bx bxl-facebook-circle'></i></a></li>
                    <li><a href="<?php echo $content_texts[11]; ?>"><i class='bx bxl-instagram-alt'></i></a></li>
                </ul>
            </div>
            <div class="footer-content">
                <p class="copyright">&copy; 2023 CAFÉ SIENA. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
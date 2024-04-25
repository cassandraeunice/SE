<?php
include '../connect.php';

$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format. Please enter a valid email address.";
    } else {
        $stmt = $con->prepare("SELECT admin_ID FROM Admin WHERE admin_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows == 0) {
            $error_message = "Email does not exist. Please enter a valid email address.";
        } else {
            header("Location: ./send_password.php?email=" . urlencode($email));
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Siena</title>
    <link rel="stylesheet" href="../../css/forgot-password.css">
    <link href='https://fonts.googleapis.com/css?family=Fanwood Text' rel='stylesheet'>
</head>
<body>

    <!-- Forgot Password Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      
        <div class="forgot-container">
        <a href="../login.php" class="btn-back"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#271300" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#271300"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path></g></svg></a>
            <p class="forgot-header">Forgot Password</p>

            <label for="email">Email</label>
            <input type="email" placeholder="Enter Email..." name="email" required onpaste="return false;">
      
            <button type="submit">Send verification code</button>

        </div>

    </form>
    <script>
    window.onload = function() {
        <?php if (!empty($error_message)): ?>
            alert('<?php echo $error_message; ?>');
        <?php endif; ?>
    };
</script>

</body>

</html>
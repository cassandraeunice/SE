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
            <p class="forgot-header">Forgot Password</p>

            <label for="email">Email</label>
            <input type="email" placeholder="Enter Email..." name="email" required>
      
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
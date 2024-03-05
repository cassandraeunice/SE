<?php
include '../connect.php';

$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPass = $_POST['oldPass'];

    // Validate oldPass format
    if (empty($oldPass)) {
        $error_message = "Please enter the current password.";
    } else {


        //Get Admin_ID
        $admin_ID = 1;

        // select update all
            $sql = "SELECT * FROM admin
                    WHERE admin_ID = ?";
        
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $admin_ID);
            $stmt->execute();
        
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();


        $storedPassword = $user['admin_password'];
        $email = $user['admin_email'];
    

        if (password_verify($oldPass, $storedPassword)) {
            header("Location: ./send_change_password.php?email=" . urlencode($email));
            exit();
        } else {
            $error_message = "Incorrect Password.";
        }
    }

    $stmt->close();
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
            <p class="forgot-header">Change Password</p>

            <label for="oldPass">Current Password</label>
            <input type="password" placeholder="Enter Current Password..." name="oldPass" required>
      
            <button type="submit">Send verification code</button>
            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

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

<?php

session_start(); // Start session to access session variables

// Check if the admin is logged in
if (!isset($_SESSION['admin_ID']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Check if the admin's login status is remembered via cookie
    if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
        // If not logged in and no remembered login status, redirect to login page
        header("Location: ../login.php");
        exit();
    }
}

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
            header("Location: ./send_change_email.php?email=" . urlencode($email));
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
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
</head>

<body>

    <!-- Forgot Password Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      
        <div class="forgot-container">
            <p class="forgot-header">Change Email</p>

            <label for="oldPass">Current Password</label>
            <input type="password" placeholder="Enter Current Password..." name="oldPass" class="oldPass" required>
            <style>
                        .oldPass {
                            width: 380px;
                            height: 50px;
                            padding: 12px 20px;
                            margin: 8px 0;
                            display: inline-block;
                            border: 1px solid #ccc;
                            box-sizing: border-box;
                            font-size: 20px;
                            font-family: 'Montserrat', sans-serif;
                            border-radius: 10px;
                            color: var(--coffee-color);
                            margin-top: 20px;
                        }
            </style>
      
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

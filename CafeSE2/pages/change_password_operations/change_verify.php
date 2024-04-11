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

$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verificationCode = $_POST["code"];

    $sql = "SELECT * FROM admin
            WHERE verification_code = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $verificationCode);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user === null) {
        $error_message = "Incorrect Verification Code";
    } else {
        // Check if the code has expired
        $codeExpiration = strtotime($user["code_expiration"]);

        if ($codeExpiration <= time()) {
            $error_message = "Code has expired";
        } else {
            // Verification code is correct, redirect to password-change.html
            header("Location: ./password_change.php");
            exit();
        }
    }

    // Close database connection
    $stmt->close();
    $con->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Siena</title>
    <link rel="stylesheet" href="../../css/forgot-verify.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
</head>

<body>

    <!-- Forgot Verify Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    
        <div class="verify-container">
            <p class="verify-header">Verification Code</p>

            <label for="code">Input Change Password Verification Code</label>
            <input class="verification-code" type="text" placeholder="Input Change Password Code..." name="code" required>
            <style>
                        .verification-code {
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

            <button type="submit">ENTER</button>
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

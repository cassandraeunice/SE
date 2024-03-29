<?php
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
        $error_message = "Verification has expired";
    } else {
        // Check if the code has expired
        $codeExpiration = strtotime($user["code_expiration"]);

        if ($codeExpiration <= time()) {
            $error_message = "Code has expired";
        } else {
            // Verification code is correct, redirect to password-change.html
            header("Location: ./email_change.php");
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
    <link href='https://fonts.googleapis.com/css?family=Fanwood Text' rel='stylesheet'>
</head>

<body>

    <!-- Forgot Verify Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    
        <div class="verify-container">
            <p class="verify-header">Verification Code</p>

            <label for="code">Input Change Email Verification Code</label>
            <input class="verification-code" type="text" placeholder="Input Change Email Code..." name="code" required>
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
                            background-color: var(--coffee-color);
                            color: var(--cream-color);
                            margin-top: 20px;
                        }
            </style>

            <button type="submit">Enter</button>
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

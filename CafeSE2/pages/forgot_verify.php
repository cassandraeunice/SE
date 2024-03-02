    <?php

    $email = '';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["email"])) {
        // Validate and sanitize the email
        $email = filter_var($_GET["email"], FILTER_VALIDATE_EMAIL);
        
        if (!$email) {
            // Redirect to the appropriate page or show an error
        }
    } else {
        // Redirect to the appropriate page or show an error
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cafe Siena</title>
        <link rel="stylesheet" href="../css/forgot-verify.css">
        <link href='https://fonts.googleapis.com/css?family=Fanwood Text' rel='stylesheet'>
    </head>

    <body>

        <!-- Forgot Verify Form -->
        <form action="forget_password_operations/reset_password.php" method="post">
        
            <div class="verify-container">
            <p class="verify-header">Verification Code</p>

            <label for="code">Input Email Verification Code</label>
            <input class="verification-code" type="text" placeholder="Input Email Code..." name="code" required>
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
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <button type="submit">Enter</button>

            </div>

        </form>

    </body>

    </html>

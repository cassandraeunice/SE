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
    <form action="ForgetPasswordOperations/reset_password.php" method="post">
      
        <div class="verify-container">
          <p class="verify-header">Verification Code</p>

          <label for="code">Input Email Verification Code</label>
          <input class="verification-code" type="text" placeholder="Input Email Code..." name="code" required>
          <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

          <button type="submit">Enter</button>

        </div>

      </form>

</body>

</html>

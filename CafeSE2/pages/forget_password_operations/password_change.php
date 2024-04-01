<?php
include '../connect.php';

$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_ID = 1;

    // Select user by ID
    $sql = "SELECT admin_password FROM admin WHERE admin_ID = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $admin_ID);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user === null) {
        $error_message = "User not found"; // Adjust the message accordingly
    } else {
        $currentPasswordHash = $user['admin_password'];
        $newPassword = $_POST["psw"];
        $confirmPassword = $_POST["confirm-psw"];

        if (strlen($newPassword) < 8 || !preg_match("/[a-z]/i", $newPassword) || !preg_match("/[0-9]/", $newPassword)) {
            $error_message = "Password must contain at least Alphanumeric (1 Uppercase & 1 Lowercase) and Symbols!";
        } elseif ($newPassword !== $confirmPassword) {
            $error_message = "Password and Confirm Password do not match";
        } elseif (password_verify($newPassword, $currentPasswordHash)) {
            $error_message = "New password cannot be the same as the current password";
        } else {
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateSql = "UPDATE admin
                SET admin_password = ?,
                    verification_code = NULL,
                    code_expiration = NULL
                WHERE admin_ID = ?";

            $updateStmt = $con->prepare($updateSql);
            $updateStmt->bind_param("si", $hashedNewPassword, $admin_ID);
            $updateStmt->execute();

            if ($updateStmt->affected_rows) {
                echo "<script>alert('Password updated successfully!'); window.location.replace('../login.php');</script>";
            } else {
                echo "<script>alert('Failed to update password'); window.location.replace('../forgot_verify.php');</script>";
            }

            // Close prepared statements
            $updateStmt->close();
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
    <link rel="stylesheet" href="../../css/password-change.css">
    <link href='https://fonts.googleapis.com/css?family=Fanwood Text' rel='stylesheet'>
</head>
<body>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="change-container">
            <p class="change-header">New Password</p>

            <label for="psw">New Password</label>
            <input type="password" placeholder="Enter password" name="psw" required>

            <label for="confirm-psw">Confirm Password</label>
            <input type="password" placeholder="Enter confirm password" name="confirm-psw" required>

            <button type="submit">CHANGE</button>

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
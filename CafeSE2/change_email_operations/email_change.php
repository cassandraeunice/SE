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
    $admin_ID = 1;
// select update all
    $sql = "SELECT * FROM Admin
            WHERE admin_ID = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $admin_ID);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();


    if ($user === null) {
        $error_message = "User not found"; // Adjust the message accordingly
    } else {
        $currentEmail = $user['admin_email'];;
        $newEmail = $_POST["email"];
        $confirmEmail = $_POST["confirm-email"];

        if ($newEmail !== $confirmEmail) {
          $error_message = "Email and Confirm Email do not match";
        } elseif ($newEmail === $currentEmail) {
            $error_message = "New email cannot be the same as the current email";
        } else {
            $updateSql = "UPDATE Admin
                SET admin_email = ?,
                    verification_code = NULL,
                    code_expiration = NULL
                WHERE admin_ID = ?";

            $updateStmt = $con->prepare($updateSql);

            $updateStmt->bind_param("si", $newEmail, $admin_ID);

            $updateStmt->execute();

            if ($updateStmt->affected_rows) {
                echo "<script>alert('Email updated successfully!'); window.location.replace('../login.php');</script>";
            } else {
                echo "<script>alert('Failed to update password'); window.location.replace('../change_verify.php');</script>";
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
    <link rel="stylesheet" href="../css/password-change.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
</head>
<body>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="change-container">
            <p class="change-header">New Email</p>

            <label for="email">New Email</label>
            <input type="email" placeholder="Enter email" name="email" class="emailTx" required onpaste="return false;">

            <label for="confirm-email">Confirm Email</label>
            <input type="email" placeholder="Enter confirm email" name="confirm-email" class="emailTx" required onpaste="return false;">

            <style>
            .emailTx {
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

            <button type="submit">Change</button>
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
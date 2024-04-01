<?php
session_start(); // Start session to persist login status

include 'connect.php';

$error_message = ""; // Initialize error message variable

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['psw'];

    // Prepare SQL statement to retrieve admin details based on email
    $stmt = $con->prepare("SELECT admin_ID, admin_password FROM Admin WHERE admin_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if email exists
    if ($result->num_rows == 1) {
        // Email exists, fetch the row
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password,$row['admin_password'])) {
            // Password is correct, set session variables
            $_SESSION['admin_ID'] = $row['admin_ID'];
            $_SESSION['logged_in'] = true;

            // Redirect to dashboard or desired page upon successful login
            header("Location: admin_menu.php");
            exit();
        } else {
            $error_message = "Invalid Email or Password";
        }
    } else {
        $error_message = "Invalid Email or Password";
    }

    // Close connections
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Siena</title>
    <link rel="stylesheet" href="../css/login.css">
    <link href='https://fonts.googleapis.com/css?family=Fanwood Text' rel='stylesheet'>
</head>
<body>

<div class="header-container">
    <p class="main-text">CAFÉ SIENA</p>
    <p class="sub-text">A taste of comfort</p>
</div>  

<div class="container">
    <!-- Blank div on the left -->
    <div class="blank-div"></div>

<!-- Login Form -->
<form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

  <div class="login-container">
      <p class="login-header">Login to your account</p>

      <label for="email">Email</label>
      <input type="email" placeholder="Enter Email" name="email" required>

      <label for="psw">Password</label>
      <input type="password" placeholder="Enter Password" name="psw" required>
      <span class="psw"><a href="./forget_password_operations/forgot_password.php">Forgot password?</a></span>

      <button type="submit">Login</button>

        </div>

    </form>
</div>

<!-- JavaScript to display alert without interrupting page -->
<script>
    window.onload = function() {
        <?php if (!empty($error_message)): ?>
            alert('<?php echo $error_message; ?>');
        <?php endif; ?>
    };
</script>

</body>
</html>

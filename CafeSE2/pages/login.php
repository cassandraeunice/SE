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
        if (password_verify($password, $row['admin_password'])) {
            // Password is correct, set session variables
            $_SESSION['admin_ID'] = $row['admin_ID'];
            $_SESSION['logged_in'] = true;

            // Set a cookie to remember the login for 3 hours
            setcookie("admin_logged_in", "true", time() + 3 * 3600, "/"); // Expires in 3 hours


            // Redirect to dashboard or desired page upon successful login
            header("Location: admin_home.php");
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
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style> 

.password-container {
            position: relative;
        }

        #passwordField {
            padding-right: 30px; 
        }

        .toggle-password {
            position: absolute;
            top: 55%;
            right: 20px; 
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 1;
            opacity: 0.8;
        }
        .toggle-password:hover {
            color: #555;
        }

input[type=email], input[type=password], input[type=text] {
  width: 380px;
  height: 40px;
  padding: 12px 20px;
  margin: 8px 0;
  margin-top: 15px;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
  font-size: 20px;
  font-family: 'Montserrat', sans-serif;
  border-radius: 10px;
  background-color: white;
  color: var(--coffee-color);
}

</style>
<body>


    <div class="container">

        <!-- Login Form -->
        <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="login-container">
                <p class="login-header">LOG IN</p>

                <label for="email">Email</label>
                <input type="email" placeholder="Enter Email" name="email" required>

                <label for="psw">Password</label>
                <div class="password-container">
                    <input type="password" placeholder="Enter Password" name="psw" id="passwordField" required >
                    <i class='bx bx-low-vision toggle-password'></i>
                </div>
                <span class="psw"><a href="./forget_password_operations/forgot_password.php">Forgot password?</a></span>

                <button type="submit">LOGIN</button>

            </div>

        </form>

        <!-- JavaScript to display alert without interrupting page -->
        <script>
            window.onload = function() {
                <?php if (!empty($error_message)) : ?>
                    alert('<?php echo $error_message; ?>');
                <?php endif; ?>
            };

                //pass visible
                $(document).on('click', '.toggle-password', function() {
        var passwordField = $('#passwordField');
        var fieldType = passwordField.attr('type');
        if (fieldType === 'password') {
            passwordField.attr('type', 'text');
            $(this).removeClass('bx-low-vision').addClass('bx-show');
        } else {
            passwordField.attr('type', 'password');
            $(this).removeClass('bx-show').addClass('bx-low-vision')
        }
    });
        </script>

</body>

</html>
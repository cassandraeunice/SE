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
        $sql = "SELECT * FROM Admin
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
    <link rel="stylesheet" href="../css/forgot-password.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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

    <!-- Forgot Password Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      
        <div class="forgot-container">
            <p class="forgot-header">Change Email</p>

            <label for="oldPass">Current Password</label>
            <div class="password-container">
                <input type="password" placeholder="Enter Current Password..." name="oldPass" class="oldPass" id="passwordField" required onpaste="return false;">
                <i class='bx bx-low-vision toggle-password'></i>
            </div>

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

        </div>

    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const passwordField = document.querySelector('.oldPass');

            togglePassword.addEventListener('click', function() {
                const fieldType = passwordField.getAttribute('type');
                if (fieldType === 'password') {
                    passwordField.setAttribute('type', 'text');
                    togglePassword.classList.remove('bx-low-vision');
                    togglePassword.classList.add('bx-show');
                } else {
                    passwordField.setAttribute('type', 'password');
                    togglePassword.classList.remove('bx-show');
                    togglePassword.classList.add('bx-low-vision');
                }
            });
        });
    </script>

</body>

</html>

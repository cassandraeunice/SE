<?php
include '../connect.php';

$error_message = ""; // Initialize error message variable

date_default_timezone_set('Asia/Manila');


$remainingTime = 0;

// Retrieve code expiration value from the database
$sql = "SELECT * FROM admin WHERE admin_ID = 1";
$stmt = $con->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$codeExpiration = strtotime($user["code_expiration"]);
$currentTimestamp = time();
$remainingTime = max(0, $codeExpiration - $currentTimestamp);

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
        } elseif ($codeExpiration != strtotime($user["code_expiration"])) {
            $error_message = "Invalid Verification Code";
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
    <link href='https://fonts.googleapis.com/css?family=Fanwood Text' rel='stylesheet'>
</head>

<style>
        .verify-container {
            position: relative;
            text-align: center;
        }

        #timerDisplay {
            margin-top: 20px;
            font-size: 24px;
            color: #333; /* Adjust color as needed */
        }

        #resendButton {
            display: none;
            margin-top: 20px;
            background-color: #8F5E36; /* Adjust background color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            height: 10vh;
        }

        #resendButton:hover {
            opacity: 0.8;
        }
    </style>

<body>

    <!-- Forgot Verify Form -->
    <form id="verifyForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="verify-container">
            <p class="verify-header">Verification Code</p>

            <label for="code">Input Email Verification Code</label>
            <input class="verification-code" type="text" placeholder="Input Email Code..." name="code" required onpaste="return false;">
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
            <button id="enterButton" type="submit">ENTER</button>

            <div id="timerDisplay"></div>
            <button id="resendButton" style="display: none;" data-email="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">Resend Verification Code</button>

        </div>
    </form>

    <script>
         var remainingTime = <?php echo $remainingTime; ?>; // Get remaining time from PHP
    console.log(remainingTime);

    function startTimer(duration, display) {
        var timer = duration;
        var minutes, seconds;

        timerInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(timerInterval); // Clear the timer interval when it reaches zero
                display.textContent = "00:00"; // Display 00:00 when timer reaches zero
                // Optionally, show or enable the resend button here
            }
        }, 1000);
    }

    window.onload = function() {
        // Display the timer on load
        var display = document.getElementById('timerDisplay');
    startTimer(<?php echo isset($remainingTime) ? $remainingTime : 0; ?>, display);
    showResendButton(<?php echo isset($remainingTime) ? $remainingTime : 0; ?>);

        // Check if there is an error message and display alert if so
        <?php if (!empty($error_message)): ?>
            alert('<?php echo $error_message; ?>');
        <?php endif; ?>
    };

    // Timer logic
    var timerInterval; // Variable to store the interval ID

    // Function to start the timer without resetting
    function startTimerWithoutReset(duration, display) {
        clearInterval(timerInterval); // Clear any existing timer interval
        startTimer(duration, display); // Start a new timer
    }

  // Show resend button after timer expiration
function showResendButton(duration) {
    setTimeout(function() {
        document.getElementById('resendButton').style.display = 'inline-block';
        // Enable enter button after timer expiration
        enterButton.disabled = false;
        enterButton.classList.remove('disabled');
        enterButton.style.cursor = 'pointer'; // Restore pointer cursor
    }, duration * 1000); // Convert seconds to milliseconds
}

    // Resend button logic
document.getElementById('resendButton').addEventListener('click', function() {
    // Hide resend button again
    document.getElementById('resendButton').style.display = 'none';
    // Reset the timer
    var duration = remainingTime; // 3 minutes
    var display = document.getElementById('timerDisplay');
    clearInterval(timerInterval); // Clear any existing timer interval
    startTimer(duration, display);

    // Make an AJAX request to fetch the email
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var email = this.responseText;
            // Redirect to send_password.php with the email parameter
            window.location.href = "./send_password.php?email=" + encodeURIComponent(email);
        }
    };
    xhr.open("GET", "./get_email.php", true);
    xhr.send();
});

</script>


</body>

</html>

<?php
include 'connect.php';

// Fetch content details for content_IDs
$content_query = "SELECT * FROM Content WHERE content_ID IN (5, 6, 7, 8, 9, 10, 11)";
$content_result = mysqli_query($con, $content_query);

// Initialize variables to store content_text and content_image for content_IDs
$content_texts = [];
$content_images = [];

// Fetch content_text and content_image for content_IDs
while ($row = mysqli_fetch_assoc($content_result)) {
  $content_texts[$row['content_ID']] = $row['content_text'];
  $content_images[$row['content_ID']] = $row['content_image'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cafe Siena</title>

  <link rel="stylesheet" href="../css/about.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> <!-- Montserrat font -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- icons -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> <!-- BootStrap -->
</head>

<body>

  <!-- NavBar -->
  <header class="header-nav">

    <a class="logo-nav">CAFÉ SIENA</a>

    <input type="checkbox" id="check">
    <label for="check" class="icons">
      <i class="bx bx-menu" id="menu-icon"></i>
      <i class="bx bx-x" id="close-icon"></i>
    </label>

    <nav class="navbar-links">
      <a href="home.php" style="--i:0;">Home</a>
      <a href="menu.php" style="--i:1;">Menu</a>
      <a href="contact_us.php" style="--i:2;">Contact Us</a>
      <a href="about.php" class="active" style="--i:3;">About</a>
    </nav>

    <!-- <i class="bx bxs-user" id="user-icon"></i> -->

  </header>

  <main>

  <section>

    <div class="about-top-container">

        <div class="contact-message">
            <p class="about-text">About</p>
            <p class="home-about">Want to know more? Discover our story!</span></p>
        </div>

    </div>

</section>

    <section>
      <div class="about-container">
        <?php
        // Display the About Us section with content from content_ID 4
        if (isset($content_texts[5]) && isset($content_images[5])) {
          echo '<div class="about-card">
                        <div class="about-body">
                        <div class="about-text-section">
                                <p class="about-card-text">' . nl2br($content_texts[5]) . '</p>
                                </div>
                            <img src="../content_images/' . $content_images[5] . '" class="card-img-top" alt="...">
                        </div>
                    </div>';
        } else {
          echo '<p>No data found for About Us section.</p>';
        }
        ?>
      </div>
    </section>

    <!-- Visit Us -->
    <section>

      <div class="visit-container">

        <div class="visit-card">

          <div class="visit-body">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.0286635131274!2d121.1780890751061!3d14.597442585888544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397bf48cfbd20ad%3A0x33171354761e1f6c!2sCaf%C3%A9%20Siena!5e0!3m2!1sen!2sph!4v1707202176017!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            <div class="visit-text-section">
              <p class="visit-title">Come visit us</p>
              <p class="visit-card-text"><i class='bx bx-map'></i><?php echo $content_texts[6]; ?></p>
              <p class="visit-card-text"><i class='bx bx-time'></i><?php echo $content_texts[7]; ?></p>
              <p class="visit-card-text"><i class='bx bx-phone'></i><?php echo $content_texts[8]; ?></p>
              <p class="visit-card-text"><i class='bx bx-envelope'></i><?php echo $content_texts[9]; ?></p>
            </div>

          </div>

        </div>

      </div>

    </section>

  </main>

  <!-- Footer -->
  <footer>

    <div class="footer-container">

      <div class="footer-content">
        <p class="footer-logo">CAFÉ SIENA</p>
      </div>

      <div class="footer-content">
        <ul class="social-icons">
          <li><a href="<?php echo $content_texts[10]; ?>"><i class='bx bxl-facebook-circle'></i></a></li>
          <li><a href="<?php echo $content_texts[11]; ?>"><i class='bx bxl-instagram-alt'></i></a></li>
        </ul>
      </div>

      <div class="footer-content">
        <p class="copyright">&copy; 2023 CAFÉ SIENA. All rights reserved.</p>
      </div>

    </div>

  </footer>

</body>

</html>
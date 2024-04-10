<?php
include 'connect.php';

// Fetch content details for content_IDs
$content_query = "SELECT * FROM Content WHERE content_ID IN (1, 2, 3, 4, 10, 11)";
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
  <link rel="stylesheet" href="../css/home.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> <!-- Montserrat font -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> <!-- BootStrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var carousel = document.getElementById('carouselFade');
      var productTitles = ['Product Title 1', 'Product Title 2', 'Product Title 3'];
      var productDescriptions = ['Description of Product 1', 'Description of Product 2', 'Description of Product 3'];

      // Function to update title and description based on the active index
      function updateContent(activeIndex) {
        var title = document.querySelector('.titleProduct');
        var description = document.querySelector('.descProduct');
        title.textContent = productTitles[activeIndex];
        description.textContent = productDescriptions[activeIndex];
      }

      // Update content for the first item on page load
      updateContent(0); // Assuming the first item is active by default

      // Event listener for slide transition
      carousel.addEventListener('slide.bs.carousel', function(event) {
        updateContent(event.to);
      });
    });
  </script>
</head>

<body>
  <main>
    <!-- NavBar -->
    <header class="header-nav">
      <a class="logo-nav">CAFÉ SIENA</a>
      <input type="checkbox" id="check">
      <label for="check" class="icons">
        <i class="bx bx-menu" id="menu-icon"></i>
        <i class="bx bx-x" id="close-icon"></i>
      </label>
      <nav class="navbar-links">
        <a href="home.php" style="--i:0;" class="active">Home</a>
        <a href="menu.php" style="--i:1;">Menu</a>
        <a href="contact_us.php" style="--i:2;">Contact Us</a>
        <a href="about.php" style="--i:3;">About</a>
      </nav>
    </header>

    <!-- Welcome to Cafe Siena -->
    <section>
      <?php
      // Use the content_image_url in the inline style for the background
      echo '<div class="welcome-container" style="background: url(../content_images/' . $content_images[1] . '); background-position: bottom; background-size: cover;">';
      ?>
      <!-- <div class="welcome-message">
        <h3>WELCOME TO</h3>
        <h1>CAFÉ SIENA!</h1>
        <h5>A taste of comfort</h5>
      </div> -->
    </section>

    <!-- Image Carousel -->
    <section>
      <div class=" col-md-12 row">
        <div class="col-md-6">
          <div id="carouselFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
              <?php
              $content_ids = [1, 2, 3];
              foreach ($content_ids as $index => $content_id) {
                // Fetch the content image for the current content ID
                $content_image = isset($content_images[$content_id]) ? $content_images[$content_id] : '';
                // Determine if the item is active or not
                $active_class = ($index === 0) ? 'active' : '';
                // Output the carousel item with the fetched image
                echo '<div class="carousel-item ' . $active_class . '">
                            <img src="../content_images/' . $content_image . '" class="d-block img-fluid" alt="...">
                        </div>';
              }
              ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselFade" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselFade" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
        <div class="col-md-6 col-12 d-flex align-items-center justify-content-center">
          <div class="content-on-right text-center ProductInformation">
            <h2 class="titleProduct"></h2>
            <p class="descProduct"></p>
          </div>
        </div>
      </div>
    </section>

    <!-- About Us -->
    <section>
      <div class="about-container">
        <?php
        // Display the About Us section with content from content_ID 4
        if (isset($content_texts[4]) && isset($content_images[4])) {
          echo '<div class="about-card">
                        <div class="about-body">
                        <div class="about-text-section">
                        <p class="about-title">About Us</p>
                                <p class="about-card-text">' . nl2br($content_texts[4]) . '</p>
                                <button class="know-more" onclick="window.location.href=\'about.php\';">Know More</button>
                                </div>
                            <img src="../content_images/' . $content_images[4] . '" class="card-img-top" alt="...">
                        </div>
                    </div>';
        } else {
          echo '<p>No data found for About Us section.</p>';
        }
        ?>
      </div>
    </section>

    <!-- Footer -->
    <footer>
      <div class="footer-container">
        <div class="footer-content">
          <p class="footer-logo">CAFÉ SIENA</p>
        </div>
        <div class="footer-content">
          <ul class="social-icons">
            <!-- Dynamic hrefs for social media icons -->
            <li><a href="<?php echo $content_texts[10]; ?>"><i class='bx bxl-facebook-circle'></i></a></li>
            <li><a href="<?php echo $content_texts[11]; ?>"><i class='bx bxl-instagram-alt'></i></a></li>
          </ul>
        </div>
        <div class="footer-content">
          <p class="copyright">&copy; 2023 CAFÉ SIENA. All rights reserved.</p>
        </div>

      </div>
    </footer>
  </main>
</body>

</html>
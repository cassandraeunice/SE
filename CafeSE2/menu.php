<?php
include 'connect.php';

// Fetch content details for content_IDs
$content_query = "SELECT * FROM Content WHERE content_ID IN (10, 11)";
$content_result = mysqli_query($con, $content_query);

// Initialize variables to store content_text for content_IDs
$content_texts = [];

// Fetch content_text and content_image for content_IDs
while ($row = mysqli_fetch_assoc($content_result)) {
  $content_texts[$row['content_ID']] = $row['content_text'];
}

// Fetch category details including category ID and image
$category_query = "SELECT category_id, category_image FROM Category";
$category_result = mysqli_query($con, $category_query);

// Initialize an array to store category details
$categories = [];

// Fetch category details
while ($row = mysqli_fetch_assoc($category_result)) {
  $categories[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cafe Siena</title>

  <link rel="stylesheet" href="css/menu.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet"> <!-- Montserrat font -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- icons -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <!-- BootStrap -->
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
      <a href="menu.php" style="--i:1;" class="active">Menu</a>
      <a href="contact_us.php" style="--i:2;">Contact Us</a>
      <a href="about.php" style="--i:3;">About</a>
    </nav>

  </header>

  <main>

    <!-- Menu-Header -->

    <section>

      <div class="menu-top-container">

        <div class="menu-message">
          <p class="menu-text">Menu</p>
          <p class="home-menu">Ready to explore? View our Menu!</p>
        </div>

      </div>

    </section>

    <!-- Menu Display -->

    <section>

      <div class="display-container">

        <div class="menu-display">

          <?php
          // Loop through categories
          foreach ($categories as $category) {
            // Extract category details
            $category_id = $category['category_id'];
            $category_image = $category['category_image'];

            // Display category image with link to category page
            echo '<div class="card-menu">
              <div class="card-body">
                <a href="category.php?category_id=' . $category_id . '">
                  <img src="category_images/' . $category_image . '" class="card-img-top" alt="...">
                </a>
              </div>
            </div>';
          }
          ?>

        </div> <!--menu display-->

      </div> <!--display container-->

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
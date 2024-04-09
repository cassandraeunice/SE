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

// Check if category ID is provided in the URL
if (isset($_GET['category_id'])) {
  $category_id = $_GET['category_id'];

  // Retrieve category name
  $category_query = "SELECT category_name FROM Category WHERE category_ID = $category_id";
  $category_result = mysqli_query($con, $category_query);
  if ($category_result && mysqli_num_rows($category_result) > 0) {
    $category_row = mysqli_fetch_assoc($category_result);
    $category_name = $category_row['category_name'];

    // Retrieve subcategories for the category
    $subcategory_query = "SELECT * FROM Subcategory WHERE category_ID = $category_id";
    $subcategory_result = mysqli_query($con, $subcategory_query);

    // If there are subcategories, fetch products for each subcategory
    if (mysqli_num_rows($subcategory_result) > 0) {
      $subcategory_products = [];
      while ($subcategory_row = mysqli_fetch_assoc($subcategory_result)) {
        $subcategory_id = $subcategory_row['subcategory_ID'];
        $subcategory_name = $subcategory_row['subcategory_name'];

        // Retrieve products associated with the subcategory
        $product_query = "SELECT * FROM Product WHERE subcategory_ID = $subcategory_id";
        $product_result = mysqli_query($con, $product_query);
        $subcategory_products[$subcategory_name] = mysqli_fetch_all($product_result, MYSQLI_ASSOC);
      }
    } else {
      // If there are no subcategories, fetch all products for the category
      $product_query = "SELECT * FROM Product WHERE category_ID = $category_id";
      $product_result = mysqli_query($con, $product_query);
      $subcategory_products = ['' => mysqli_fetch_all($product_result, MYSQLI_ASSOC)];
    }
  } else {
    // Category not found
    echo "Category not found.";
  }
} else {
  // Category ID not provided in URL
  echo "Category ID not provided.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cafe Siena</title>

  <link rel="stylesheet" href="../css/category.css">
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
      <a href="menu.php" style="--i:1;" class="active">Menu</a>
      <a href="contact_us.php" style="--i:2;">Contact Us</a>
      <a href="about.php" style="--i:3;">About</a>
    </nav>

    <!-- <i class="bx bxs-user" id="user-icon"></i> -->

  </header>

  <main>

    <!-- Menu-Header -->

    <section>
      <div class="menu-top-container">
        <div class="menu-message">
          <?php if (isset($category_name)) : ?>
            <p class="menu-text"><?php echo $category_name; ?></p>
          <?php else : ?>
            <p class="menu-text">Categories</p>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Menu Display -->

    <section>
      <div class="display-container">
        <?php foreach ($subcategory_products as $subcategory_name => $products) : ?>
          <div class="menu">
            <?php if (!empty($subcategory_name)) : ?>
              <div class="heading">
                <h2><?php echo $subcategory_name; ?></h2>
              </div>
            <?php else : ?>
              <div class="heading" style="padding: 0%;"></div>
            <?php endif; ?>
            <?php if (!empty($products)) : ?>
              <?php foreach ($products as $product) : ?>
                <div class="food-items">
                  <!-- Display product image -->
                  <img src="<?php echo '../menu_images/' . $product['product_image']; ?>" class="card-img-top" alt="Product Image">
                  <div class="details">
                    <!-- Display product name -->
                    <div class="details-sub">
                      <h5><?php echo $product['product_name']; ?></h5>
                      <!-- Display product price -->
                      <h5 class="price">₱<?php echo $product['product_price']; ?></h5>
                    </div>
                    <!-- Display product description -->
                    <p><?php echo $product['product_description']; ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else : ?>
              <p><?php echo !empty($subcategory_name) ? 'No products available for this subcategory.' : 'No products available for this category.';
                  "<i class='bx bx-error-circle'></i>" ?></p>
            <?php endif; ?>
          </div> <!-- Menu -->
        <?php endforeach; ?>
      </div> <!-- Display Container -->
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
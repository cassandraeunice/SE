<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- icons -->
    <title>Admin Dashboard</title>
</head>
<body>
    <section id="sidebar">
        <a href="#" class="brand">
            <i class="bx bxs-smile"></i>
            <span class="text">Admin Dashboard</span> 
        </a>
        <ul class="side-menu">
            <li class="active">
                <a href="#">
                    <i class="bx bxs-food-menu"></i>
                    <span class="text">Menu Content</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bx bxs-home-circle"></i>
                    <span class="text">Home Content</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bx bxs-book"></i>
                    <span class="text">Feedback Content</span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="bx bxs-chevrons-left"></i>
                    <span class="text">Return to Website</span>
                </a>
            </li>

            <li>
                <a href="#" class="logout">
                    <i class="bx bxs-log-out-circle"></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>

    </section>

    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search"><i class="bx bx-search"></i></button>
                </div>
            </form>
        </nav>

    </section>



    <script src="../Script/dashboard.js"></script>
</body>



</html>

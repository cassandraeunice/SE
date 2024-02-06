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
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search"><i class="bx bx-search"></i></button>
                </div>
            </form>
        </nav>


        <main>
        <div id="menu-content" class="content-section">
            <div class="head-title">
                <div class=left>
                    <h1>Menu Content</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Admin Dashboard</a>

                        </li>
                        <li><i class="bx bx-chevron-right"></i></li>
                        <li>
                            <a href="#" class="active">Menu Content</a>
                        </li>
                    </ul>
                </div>
                <a href="#" class="btn-download">
                    <i class="bx bxs-cloud-download"></i>
                    <span class="text">Download PDF</span>
                </a>
            </div>

            <!---TABLE--->
            <div class="table-data">
                    <div class="menu">
                        <div class="head">
                            <h3>Menu Items</h3>
                            <i class="bx bx-filter"></i>
                            <i class="bx bx-plus"></i>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Item No.</th>
                                    <th>Menu Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Rating</th>
                                    <th>Edit</th>
                                    <th>Delete</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <img src="../images/Coffee-icon.png">
                                        
                                    </td>
                                    <td>1</td>
                                    <td>White Mocha</td>
                                    <td>Milky Coffee</td>
                                    <td>75</td>
                                    <td>4.4</td>
                                    <td><span class="btn edit">Edit</span></td>
                                    <td><span class="btn delete">Delete</span></td>
                                    
                                </tr>

                                <tr>
                                    <td>
                                        <img src="../images/Coffee-icon.png">
                                        
                                    </td>
                                    <td>2</td>
                                    <td>Dark Mocha</td>
                                    <td>Dark Coffee</td>
                                    <td>85</td>
                                    <td>4.1</td>
                                    <td><span class="btn edit">Edit</span></td>
                                    <td><span class="btn delete">Delete</span></td>
                                    
                                </tr>

                                <tr>
                                    <td>
                                        <img src="../images/Coffee-icon.png">
                                        
                                    </td>
                                    <td>3</td>
                                    <td>Caramel Macchiato</td>
                                    <td>Caramelized Milky Coffee</td>
                                    <td>105</td>
                                    <td>4.6</td>
                                    <td><span class="btn edit">Edit</span></td>
                                    <td><span class="btn delete">Delete</span></td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>  
        </div>

        <div id="home-content" class="content-section">
            <div class="head-title">
                <div class=left>
                    <h1>Home Content</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Admin Dashboard</a>

                        </li>
                        <li><i class="bx bx-chevron-right"></i></li>
                        <li>
                            <a href="#" class="active">Home Content</a>
                        </li>
                    </ul>
                </div>
                <a href="#" class="btn-download">
                    <i class="bx bxs-cloud-download"></i>
                    <span class="text">Download PDF</span>
                </a>
            </div>

            <!---TABLE--->
            <div class="table-data">
                    <div class="menu">
                        <div class="head">
                            <h3>Home Items</h3>
                            <i class="bx bx-filter"></i>
                            <i class="bx bx-plus"></i>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Content No.</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date Posted</th>
                                    <th>Edit</th>
                                    <th>Delete</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <img src="../images/Coffee-icon.png">
                                        
                                    </td>
                                    <td>1</td>
                                    <td>Holiday Season</td>
                                    <td>Discounts</td>
                                    <td>1/28/24</td>
                                    <td><span class="btn edit">Edit</span></td>
                                    <td><span class="btn delete">Delete</span></td>
                                    
                                </tr>

                                <tr>
                                    <td>
                                        <img src="../images/Coffee-icon.png">
                                        
                                    </td>
                                    <td>2</td>
                                    <td>New Year</td>
                                    <td>New Menu Items</td>
                                    <td>1/29/24</td>
                                    <td><span class="btn edit">Edit</span></td>
                                    <td><span class="btn delete">Delete</span></td>
                                    
                                </tr>

                                <tr>
                                    <td>
                                        <img src="../images/Coffee-icon.png">
                                        
                                    </td>
                                    <td>3</td>
                                    <td>Valentines!!!</td>
                                    <td>Great Deals</td>
                                    <td>2/14/24</td>
                                    <td><span class="btn edit">Edit</span></td>
                                    <td><span class="btn delete">Delete</span></td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>  
        </div>
        </main>

    </section>



    <script src="../Script/dashboard.js"></script>
</body>



</html>

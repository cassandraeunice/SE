
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
                            <i class="bx bx-plus" id="addValueIcon"></i>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Item No.</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Edit</th>
                                    <th>Delete</th>

                                </tr>
                            </thead>
                            <tbody id="tableBody">
                             
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

            
        </div>
        </main>

    </section>

    <script src="../Script/dashboard.js"></script>
    
    <script>

function addItem() {
    // Retrieve form data
    var product_name = document.getElementById('ProductName').value;
    var product_img = document.getElementById('itemImage').files[0]; // Get the selected image file
    var product_description = document.getElementById('Description').value;
    var product_price = document.getElementById('Price').value;

    // Create a new FormData object to send data in the POST request
    var formData = new FormData();
    formData.append('product_name', product_name);
    formData.append('product_img', product_img); // Append the image file to the FormData
    formData.append('product_description', product_description); // Corrected line
    formData.append('product_price', product_price);

    // Make an AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_menu.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText);

            // Notify the user
            alert("Item added successfully");

            // Close the popup
            closePopup();

            // Fetch and display the most recently added item
            displayRecentlyAdded();
        } else {
            console.error(xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Network error');
    };

    // Send the request with form data
    xhr.send(formData);
}
document.addEventListener('DOMContentLoaded', function () {
    // Fetch and display recently added items when the document is fully loaded
    displayRecentlyAdded();
});

function displayRecentlyAdded() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_all_items.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);

            if (data && data.length > 0) {
                // Clear existing rows in the table
                var tbody = document.getElementById('tableBody');
                tbody.innerHTML = '';

                // Iterate through all rows and add them to the table
                for (var i = 0; i < data.length; i++) {
                    var newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${data[i].product_ID}</td>
                        <td><img src="${data[i].product_img}" alt="Image" style="width: 50px; height: 50px;"></td>
                        <td>${data[i].product_name}</td>
                        <td>${data[i].product_description}</td>
                        <td>${data[i].product_price}</td>
                        <td><span class="btn edit" onclick="openEditPopup(${data[i].product_ID})">Edit</span></td>
                        <td><span class="btn delete" onclick="deleteItem(${data[i].product_ID})">Delete</span></td>
                    `;

                    // Add the new row to the tbody
                    tbody.appendChild(newRow);
                }
            }
        } else {
            console.error('Error fetching recently added data:', xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Network error');
    };

    // Send the request to get all items
    xhr.send();
}



// Call displayRecentlyAdded after the document has fully loaded
document.addEventListener('DOMContentLoaded', function () {
    displayRecentlyAdded();
});





    function closePopup() {
            var cardContainers = document.querySelectorAll(".popup-card-container");
            cardContainers.forEach(function (cardContainer) {
                cardContainer.style.display = "none";
        document.body.removeChild(cardContainer);
            });
        }

    document.addEventListener('DOMContentLoaded', function () {
        var addValueIcons = document.querySelectorAll(".bx-plus");

        addValueIcons.forEach(function (addValueIcon) {
            addValueIcon.addEventListener("click", function () {
                // Create the card container
                var cardContainer = document.createElement("div");
                cardContainer.classList.add("popup-card-container");

                // Create the card content
                var cardContent = document.createElement("div");
                cardContent.classList.add("popup-card-content");

                cardContent.innerHTML = `
                <form method="post" enctype="multipart/form-data">
                <fieldset class="border-menu">
                    <legend>Add Item to Menu:</legend>
                    <label for="itemImage">Upload Image:</label>
                    <div>
                        <input type="file" id="itemImage" name="itemImage" accept="image/*" class="form-control">

                    </div>
                    <br>

                    <label for="ProductName">Menu Name:</label>
                    <div>
                    <input type="text" id="ProductName" name="ProductName" class="form-control">
                    </div>

                    <br>

                    <label for="Description">Description:</label>
                    <div>
                    <textarea type="text" id="Description" name="Description" class="form-control" rezisable="false"></textarea>
                    </div>


                    <br>

                    <label for="Price">Price:</label>
                    <div>
                    <input type="number" id="Price" name="Price" class="form-control"><br>
                    </div>

                    <br>
                    
                    <div class="btn-container">
                    <button class="btn-close" type="button" onclick="closePopup()">Close</button>
                    <button class="btn-add" type="button" onclick="addItem()">Add Item</button>

                    </fieldset>
                    </form>

                    <style>  
                    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');
                    :root{
                    --poppins: 'Poppins', sans-serif;
                    --lato: 'Lato', sans-serif;
                    }
                    *{
                        font-family: var(--poppins);
                    }
                    legend{
                        
                        font-size: 15px;
                        font-weight:bold;

                    }
                    .border-menu{
                        border-radius:10px;
                        padding: 20px;
                        margin: 5px;
                    
                    }
                        .btn-container {
                        display: flex;
                        justify-content: center;
                        align-tems: center;
                        border-radius:10px; 

                    }     
                        .btn-add{
                            background-color: #3C91E6;
                            color: white;
                            padding:10px;
                            border-color: white;
                            display:flex;
                            margin:5px;
                            border-radius:10px;

                        }

                        .btn-close{
                            background-color:#DB504A;
                            color: white;
                            padding:10px;
                            border-color: white;
                            display:flex;
                            margin:5px;
                            border-radius:10px;

                        }
                        .form-control{
                            width:100%;
                            font-size: 12pt;
                        }
                        textarea{
                            resize: none;
                            height: 60px;
                        }
                    </style>
                `;
                cardContent.style.backgroundColor = "#fff";  // Set background color to white
                cardContent.style.padding = "20px";          // Add padding for better visibility
                cardContent.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";  // Add box shadow
                cardContent.style.borderRadius = "10px";    
                cardContainer.style.position = "fixed";
                cardContainer.style.top = "50%";
                cardContainer.style.left = "50%";
                cardContainer.style.transform = "translate(-50%, -50%)";
                cardContainer.appendChild(cardContent);
                document.body.appendChild(cardContainer);
                cardContainer.style.zIndex = "9999";
            });
        });


    
        
    });




    document.addEventListener('DOMContentLoaded', function () {
    var editValueIcons = document.querySelectorAll(".btn.edit");

    editValueIcons.forEach(function (editValueIcon) {
        editValueIcon.addEventListener("click", function () {
            // Get the itemNo associated with the clicked edit button
            var product_ID = getProduct_IDFromEditButton(editValueIcon);

            // Call the openEditPopup function with the itemNo
            openEditPopup(product_ID);
        });
    });
});

function getProduct_IDFromEditButton(editValueIcon) {
    // Extract and return the itemNo from the DOM element or any other method based on your structure
    // For example, if your itemNo is stored as a data attribute, you can use:
    // return editValueIcon.getAttribute('data-itemNo');
    // Adjust this based on how your itemNo is associated with the "Edit" button.
}

function openEditPopup(product_ID) {
    // Create the card container
    var cardContainer = document.createElement("div");
    cardContainer.classList.add("popup-card-container");

    // Create the card content
    var cardContent = document.createElement("div");
    cardContent.classList.add("popup-card-content");

    // Fetch item details from the server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_all_items.php?product_ID=' + product_ID, true);
    xhr.onload = function () {
    if (xhr.status === 200) {
        var itemDetails = JSON.parse(xhr.responseText);

        // Update form fields with the fetched item details
        var productNameEdit = document.querySelector('#ProductNameEdit');
        var descriptionEdit = document.querySelector('#DescriptionEdit');
        var priceEdit = document.querySelector('#PriceEdit');

        if (productNameEdit && descriptionEdit && priceEdit) {
            productNameEdit.value = itemDetails.product_name || '';
            descriptionEdit.value = itemDetails.product_description || '';
            priceEdit.value = itemDetails.product_price || '';
        } else {
            console.error('One or more form elements not found.');
        }

        // If there is a new image, update the image field
        if (itemDetails.NewImage) {
            var itemImageEdit = document.getElementById('itemImageEdit');
            if (itemImageEdit) {
                itemImageEdit.src = itemDetails.NewImage;
            } else {
                console.error('Image element not found.');
            }
        }
    
            // Continue with form creation
            cardContent.innerHTML = `
                <form method="post" enctype="multipart/form-data">
                    <fieldset class="border-menu">
                        <legend>Edit Item:</legend>
                        <label for="itemImageEdit">Upload Image:</label>
                        <div>
                            <input type="file" id="itemImageEdit" name="itemImage" accept="image/*" class="form-control">
                        </div>
                        <br>

                        <label for="ProductNameEdit">Menu Name:</label>
                        <div>
                            <input type="text" id="ProductNameEdit" name="ProductName" class="form-control">
                        </div>
                        <br>

                        <label for="DescriptionEdit">Description:</label>
                        <div>
                            <textarea type="text" id="DescriptionEdit" name="Description" class="form-control" rezisable="false"></textarea>
                        </div>
                        <br>

                        <label for="PriceEdit">Price:</label>
                        <div>
                            <input type="number" id="PriceEdit" name="Price" class="form-control"><br>
                        </div>
                        <br>
                        
                        <div class="btn-container">
                            <button class="btn-close" type="button" onclick="closePopup()">Close</button>
                            <button class="btn-edit" type="button" onclick="updateItem(${product_ID})">Edit Item</button>
                        </div>
                    </fieldset>
                </form>

                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');
                    :root {
                        --poppins: 'Poppins', sans-serif;
                        --lato: 'Lato', sans-serif;
                    }
                    * {
                        font-family: var(--poppins);
                    }
                    legend {
                        font-size: 15px;
                        font-weight: bold;
                    }
                    .border-menu {
                        border-radius: 10px;
                        padding: 20px;
                        margin: 5px;
                    }
                    .btn-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        border-radius: 10px;
                    }
                    .btn-edit {
                        background-color: #3C91E6;
                        color: white;
                        padding: 10px;
                        border-color: white;
                        display: flex;
                        margin: 5px;
                        border-radius: 10px;
                    }
                    .btn-close {
                        background-color: #DB504A;
                        color: white;
                        padding: 10px;
                        border-color: white;
                        display: flex;
                        margin: 5px;
                        border-radius: 10px;
                    }
                    .form-control {
                        width: 100%;
                        font-size: 12pt;
                    }
                    textarea {
                        resize: none;
                        height: 60px;
                    }
                </style>
            `;

            // Continue with styling and appending elements to the document
            cardContent.style.backgroundColor = "#fff";
            cardContent.style.padding = "20px";
            cardContent.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
            cardContent.style.borderRadius = "10px";
            cardContainer.style.position = "fixed";
            cardContainer.style.top = "50%";
            cardContainer.style.left = "50%";
            cardContainer.style.transform = "translate(-50%, -50%)";
            cardContainer.appendChild(cardContent);
            document.body.appendChild(cardContainer);
            cardContainer.style.zIndex = "9999";
        }
    };

    xhr.send();
}


function editItem(product_ID) {
    // Fetch item details from the server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_all_items.php?product_ID=' + product_ID, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var itemDetails = JSON.parse(xhr.responseText);

            // Update form fields with the fetched item details
            // Inside editItem function
            document.querySelector('#ProductNameEdit').value = itemDetails.product_name;
document.querySelector('#DescriptionEdit').value = itemDetails.product_description;
document.querySelector('#PriceEdit').value = itemDetails.product_price;



            // If there is a new image, update the image field
            if (itemDetails.NewImage) {
                // Assuming you have an image element with id 'itemImage' to display the updated image
                document.getElementById('itemImage').src = itemDetails.NewImage;
            }

            // Notify the user
            alert("Item details loaded for editing");

            // Close the popup
            closePopup();

            // Fetch and display the most recently added item
            displayRecentlyAdded();
        } else {
            console.error('Error fetching item details:', xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Network error');
    };

    // Send the request to get item details
    xhr.send();
}


function updateItem(product_ID) {
    // Retrieve form data
    var product_name = document.getElementById('ProductNameEdit').value;
    var product_img = document.getElementById('itemImageEdit').files[0]; // Get the selected image file
    var product_description = document.getElementById('DescriptionEdit').value;
    var product_price = document.getElementById('PriceEdit').value;

    // Create a new FormData object to send data in the POST request
    var formData = new FormData();
    formData.append('product_name', product_name);
    formData.append('product_img', product_img);
    formData.append('product_description', product_description);
    formData.append('product_price', product_price);

    // Make an AJAX request to update the item
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'edit_menu.php?product_ID=' + product_ID, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText);

            // Notify the user
            alert("Item updated successfully");

            // Close the popup
            closePopup();

            // Fetch and display the most recently added item
            displayRecentlyAdded();
        } else {
            console.error(xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Network error');
    };

    // Send the request with form data
    xhr.send(formData);
}

function deleteItem(product_ID) {
    // Confirm with the user before proceeding with the deletion
    var confirmDelete = confirm("Are you sure you want to delete this item?");
    
    if (confirmDelete) {
        // Make an AJAX request to the PHP script for deletion
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_menu.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        // Handle the response from the server
        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                if (response.success) {
                    // Item deleted successfully, you may want to update the UI
                    alert('Item deleted successfully');
                    displayRecentlyAdded();
                } else {
                    // Handle deletion failure
                    alert('Failed to delete item');
                }
            } else {
                console.error('Error in AJAX request:', xhr.status);
            }
        };

        // Handle network errors
        xhr.onerror = function () {
            console.error('Network error during AJAX request');
        };

        // Send the request with the item number as data
        xhr.send('product_ID=' + product_ID);
    }
}


</script>





 
</body>



</html>

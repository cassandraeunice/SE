document.addEventListener('DOMContentLoaded', function () {
    const allSideMenu = document.querySelectorAll('#sidebar .side-menu li a');
    const contentSections = document.querySelectorAll('#content main .content-section');
    
    // Show the "Menu Content" section by default
    const defaultSection = document.getElementById('menu-content');
    if (defaultSection) {
        defaultSection.classList.add('active');
    }

    allSideMenu.forEach((item, index) => {
        item.addEventListener('click', function (event) {
            event.preventDefault();

            // Remove 'active' class from all side menu items
            allSideMenu.forEach(i => {
                i.parentElement.classList.remove('active');
            });

            // Add 'active' class to the clicked side menu item
            const li = item.parentElement;
            li.classList.add('active');

            // Remove 'active' class from all content sections
            contentSections.forEach(section => {
                section.classList.remove('active');
            });

            // Add 'active' class to the corresponding content section based on the index
            contentSections[index].classList.add('active');
        });
    });
});







const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sideBar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
    sideBar.classList.toggle('hide');
});

function handleResize() {
    if (window.innerWidth < 768) {
        sideBar.classList.add('hide');
    }else {
        sideBar.classList.remove('hide');
    }
}

// Initial call to set the initial state based on the window width
handleResize();

// Adding resize event listener
window.addEventListener('resize', handleResize);


const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
    if (window.innerWidth < 768) {
        e.preventDefault();
        searchForm.classList.toggle('show');
        if(searchForm.classList.contains('show')){
            searchButtonIcon.classList.replace('bx-search', 'bx-x');

        }else{
            searchButtonIcon.classList.replace('bx-x', 'bx-search');
        }


        }
    })


    window.addEventListener('resize', function(){
        if(window.innerWidth > 768){
            searchButtonIcon.classList.replace('bx-x','bx-search');
            searchForm.classList.remove('show');
        }
    })

    
 
    

    
    
    
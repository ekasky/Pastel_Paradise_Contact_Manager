// Get the html elements by id needed
const nav            = document.getElementById('moblie-nav');
const mobileMenuBtn  = document.getElementById('mobile-menu-btn');
const icon           = document.getElementById('mobile-menu-icon');

// Listen for mobile menu btn click
mobileMenuBtn.addEventListener('click', () => {

    // Open or close the mobile menu
    if(nav.classList.contains('hidden')) {

        nav.classList.remove('hidden');
        nav.classList.add('flex');
        icon.src = './assets/icons/xmark-solid.svg';

    }
    else {

        nav.classList.remove('flex');
        nav.classList.add('hidden');
        icon.src = './assets/icons/bars-solid.svg';

    }

});
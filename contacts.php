<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'api/middleware.php';

try {

    // Create connection to the database
    $conn = connect();

    // validate the users session
    $valid_session = valid_session($conn);

    // Get the users record given the session record
    $user          = get_user_from_session_id($valid_session, $conn);

    // Extract user info
    $first_name    = $user['first_name'];
    $last_name     = $user['last_name'];
    $email         = $user['email'];

}
catch(Exception $e) {

    // If there is a error redirect to login page
    header('Location:login.html');

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastel Paradise >> Contact Dashboard</title>
    <link rel="stylesheet" href="output.css">
    <link rel="icon" type="image/x-icon" href="./assets/logos/logo2_orginal_cropped-transparent-bg_icon.ico">
</head>

<body class="relative font-Mochiy font-extralight">
    
    <!-- Navbar Section  -->

    <header class="flex items-center justify-between lg:px-8 relative h-12 lg:h-16">

    <a href="/contacts.php" id="navbar-logo" class="flex flex-row items-center gap-2 text-lg lg:text-xl font-bold text-black ml-2">
        <img src="./assets/logos/paradise-logo.png" alt="ContactKeepr Logo" class="w-16 lg:w-24">
    </a>

    <nav id="destop-nav" class="hidden lg:flex lg:flex-row lg:items-center lg:gap-8" aria-label="desktop-nav">

        <a href="/contacts.php" class="text-sm text-gray-800 hover:text-gray-600">Contacts</a>
        <a href="/settings.html" class="text-sm text-gray-800 hover:text-gray-600">Settings</a>
        <div>
            <a href="/new-contact.php" class="bg-gradient-to-br from-pink-300 to-rose-300 text-pink-800 font-bold px-4 py-2 shadow-md rounded-lg hover:bg-gradient-to-br hover:from-pink-300 hover:to-purple-300 focus:bg-gradient-to-br focus:from-pink-300 focus:to-purple-300">New Contact</a>
            <button id="logout-btn" class="bg-gradient-to-br from-pink-300 to-rose-300 text-pink-800 font-bold px-4 py-2 shadow-md rounded-lg hover:bg-gradient-to-br hover:from-pink-300 hover:to-purple-300 focus:bg-gradient-to-br focus:from-pink-300 focus:to-purple-300">Logout</button>
        </div>

    </nav>

    <button class="h-full w-12 flex items-center justify-center hover:bg-slate-100 lg:hidden" id="mobile-menu-btn"><img src="./assets/icons/bars-solid.svg" alt="Menu Open" id="mobile-menu-icon" class="w-4"></button>

    <nav id="moblie-nav" class="absolute top-full left-0 bg-white w-full hidden flex-col gap-4 p-4 shadow-lg lg:hidden" aria-label="moblie-nav">
        
        <a href="/contacts.php" class="text-sm text-gray-800 hover:text-gray-600">Contacts</a>
        <a href="/settings.html" class="text-sm text-gray-800 hover:text-gray-600">Settings</a>
        <hr>
        <a href="/new-contact.php" class="text-sm text-gray-800 hover:text-gray-600">New Contact</a>
        <button id="logout-btn-mobile" class="text-sm text-gray-800 hover:text-gray-600 flex items-start">Logout</button>

    </nav>

    </header>

    <main class="bg-gradient-to-br from-pink-300 to-rose-300 py-8 mt-8">

        <div class="m-8">
            <h1 class="flex items-center justify-center text-2xl mb-2 text-pink-800"><?php echo $first_name ?>'s Contacts</h1>
        </div>

        <!-- Search bar -->

        <section class="py-4 mt-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <form id="search-form">
                    <input id="search" type="text" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-500" placeholder="Search contacts...">
                </form>
            </div>
        </section>

        <!-- Contact List -->

        <section id="contact-container" class=" grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 px-4 py-8">
            
        </section>

    </main>

    <!-- Footer Section -->
    <footer class="bg-gradient-to-br from-pink-400 to-purple-400 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
            <div class="text-center md:text-left mb-4 md:mb-0">
                <p>&copy; 2024 Pastel Paradise. All rights reserved.</p>
            </div>
            <div class="text-center md:text-right">
                <ul class="flex flex-wrap justify-center md:justify-end space-x-4">
                    <li><a href="#" class="hover:text-black">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-black">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-black">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="./js/mobile-menu.js"></script>
    <script src="./js/logout.js"></script>
    <script src="./js/get-all-contacts.js"></script>
</body>  
</html>

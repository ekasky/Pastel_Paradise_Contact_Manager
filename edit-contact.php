<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once './api/middleware.php';

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
    <title>ContactKeepr >> Edit Contact</title>
    <link rel="stylesheet" href="output.css">
    <link rel="icon" type="image/x-icon" href="./assets/logos/logo2_orginal_cropped-transparent-bg_icon.ico">
</head>
<body>

    <!-- Navbar Section  -->

    <header class="flex items-center justify-between lg:px-8 relative h-12 lg:h-16">

    <a href="/contacts.php" id="navbar-logo" class="flex flex-row items-center gap-2 text-lg lg:text-xl font-bold text-black ml-2">
        <img src="./assets/logos/logo2_cropped_transparent_bg.webp" alt="ContactKeepr Logo" class="w-8 lg:w-10">
        <h1 class="hover:text-gray-600">ContactKeepr</h1>
    </a>

    <nav id="destop-nav" class="hidden lg:flex lg:flex-row lg:items-center lg:gap-8" aria-label="desktop-nav">

        <a href="/contacts.php" class="text-sm text-gray-800 hover:text-gray-600">Contacts</a>
        <a href="/settings.html" class="text-sm text-gray-800 hover:text-gray-600">Settings</a>
        <div>
            <a href="/new-contact.php" class="bg-blue-600 text-white font-bold px-4 py-2 shadow-md rounded-lg hover:bg-blue-700 focus:bg-blue-700">New Contact</a>
            <button id="logout-btn" class="bg-red-600 text-white font-bold px-4 py-2 shadow-md rounded-lg hover:bg-red-700 focus:bg-red-700">Logout</button>
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

    <section class="flex items-center justify-center h-screen bg-gradient-to-b from-cyan-500 to-cyan-700 bg-cover bg-center bg-fixed">
        
        <div class="max-w-lg mx-auto px-8 py-8 bg-white rounded-lg shadow-md">
            
            <h2 class="text-3xl font-semibold mb-4 text-center">Edit Contact</h2>

            <div id="error-box" class="w-full bg-red-300 p-4 rounded-md mb-4 shadow-md hidden"></div>
            <div id="success-box" class="w-full bg-green-400 p-4 rounded-md mb-4 shadow-md hidden"></div>

            <form id="edit-contact-form">

                <div class="grid grid-cols-2 gap-4 mb-4">

                    <div>
                        <label for="first-name" class="block text-gray-800 font-semibold mb-2">First Name<span class="text-red-500">*</span></label>
                        <input type="text" id="first-name" name="first-name" class="w-full px-4 py-2 rounded-lg shadow-md focus:outline-none focus:ring focus:border-blue-500" placeholder="Enter first name">
                    </div>

                    <div>
                        <label for="last-name" class="block text-gray-800 font-semibold mb-2">Last Name<span class="text-red-500">*</span></label>
                        <input type="text" id="last-name" name="last-name" class="w-full px-4 py-2 rounded-lg shadow-md focus:outline-none focus:ring focus:border-blue-500" placeholder="Enter last name">
                    </div>

                </div>

                <div class="mb-4">

                    <label for="phone-number" class="block text-gray-800 font-semibold mb-2">Phone Number<span class="text-red-500">*</span></label>
                    <input type="text" id="phone-number" name="phone-number" class="w-full px-4 py-2 rounded-lg shadow-md focus:outline-none focus:ring focus:border-blue-500" placeholder="Enter phone number">
                
                </div>

                <div class="mb-4">

                    <label for="email" class="block text-gray-800 font-semibold mb-2">Email<span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 rounded-lg shadow-md focus:outline-none focus:ring focus:border-blue-500" placeholder="Enter email">
                
                </div>

                <div class="text-center">

                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-300 ease-in-out">Update Contact</button>
                
                </div>

            </form>

        </div>

    </section>

    <!-- Footer Section -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
            <div class="text-center md:text-left mb-4 md:mb-0">
                <p>&copy; 2024 ContactKeepr. All rights reserved.</p>
            </div>
            <div class="text-center md:text-right">
                <ul class="flex flex-wrap justify-center md:justify-end space-x-4">
                    <li><a href="#" class="hover:text-blue-500">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-blue-500">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-blue-500">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="./js/mobile-menu.js"></script>
    <script src="./js/logout.js"></script>
    <script src="./js/edit-contact.js"></script>

</body>
</html>
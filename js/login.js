// Get login form by id
let form = document.getElementById('login-form');

// Get the error message div
let error_box = document.getElementById('error-message-box');

// Add event listener for the form submit action
form.addEventListener('submit', (event) => {

    // Prevent the default page refresh
    event.preventDefault();

    // Get the email address and password from the form
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;

    // Use Regex to verfiy email address is in valid form
    const pattern = /^\S+@\S+\.\S+$/;

    if(!pattern.test(email)) {

        error_box.textContent = 'Invalid email address format';
        error_box.classList.remove('d-none');
        return;

    }


    // Prepare the request body
    let body = {
        email, password
    };

    // Send POST AJAX request
    fetch("/api/login.php", {
        
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(body)

    })
    .then(res => {

        return res.json();

    })
    .then(data => {

        if(data.error) {
            
            error_box.textContent = data.error;
            error_box.classList.remove('d-none');

            return;
        }
         
        // Make redirect request to ensure user is valid
        window.location.href = '/dashboard.php';

    })
    .catch(error => {

        console.log(error);

    });
    

});
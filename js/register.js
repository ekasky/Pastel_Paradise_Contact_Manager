// Get register form by id
let form = document.getElementById('login-form');

// Get the error message box by id
let error_box = document.getElementById('error-message-box');

// Add event listerner for register form submit
form.addEventListener('submit', (event) => {

    // Prevent the default page refresh
    event.preventDefault();

    // Get the first_name, last_name, email, and password from the form
    let first_name = document.getElementById('first_name').value;
    let last_name = document.getElementById('last_name').value;
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
        first_name, last_name, email, password
    };

    // Send POST AJAX request to register endpoint
    fetch('/api/register.php', {

        method: 'POST',
        headers: {
            'Content-Type':'application/json'
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
        window.location.href = '/index.html?success=true';

    })
    .catch(error => {

        console.log(error);

    });

});
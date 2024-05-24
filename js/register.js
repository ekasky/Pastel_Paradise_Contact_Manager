// Get the form id
let form = document.getElementById('register-form');

// Stores the error messages
let error_msg = document.getElementById('form-error-msg');

document.addEventListener('submit', (event) => {

    event.preventDefault();

    // Get the fields from the form
    let first_name = document.getElementById('register-form-first_name').value;
    let last_name = document.getElementById('register-form-last_name').value;
    let username = document.getElementById('register-form-username').value;
    let password = document.getElementById('register-form-password').value;

    // Prepare the request body
    let body = {
        first_name,
        last_name,
        username,
        password
    };

    // Make a AJAX request to register endpoint
    fetch("/api/register.php", {

        method: "POST",
        headers: {
            'Content-Type':'application/json'
        },
        body: JSON.stringify(body)

    })
    .then(res => {

        if(!res.ok) {
            return res.json().then(error => {throw new Error(error.error)});
        }

        return res.json();

    })
    .then(data => {

        if(data.Missing) {

            error_msg = document.getElementById('form-error-msg');
            error_msg.innerHTML = 'Required: ' + data.Missing;

        }
        else {

            error_msg.innerHTML = " ";
            window.location.href = '/index.php';

        }

    })
    .catch(error => {

        error_msg = document.getElementById('form-error-msg');
        error_msg.innerHTML = error;

    });

});
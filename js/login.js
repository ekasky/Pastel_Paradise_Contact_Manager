// Get the login form by id
let form = document.getElementById('login-form');

let error_msg = document.getElementById('form-error-msg');

// Add event listener for form submit
form.addEventListener('submit', (event) => {

    event.preventDefault();

    // Get the username and password from the form
    let username = document.getElementById('login-form-username').value;
    let password = document.getElementById('login-form-password').value;

    // Prepare the request body
    let body = {
        username,
        password
    };

    // Make a AJAX request to login user with supplied credentials
    fetch('/api/login.php', {

        method: "POST",
        headers: {
            'Content-Type':'application/json'
        },
        body: JSON.stringify(body)

    })
    .then( res => {

        if(!res.ok) {
            
            return res.json().then(error => {throw new Error(error.error)});
            

        }

        return res.json();

    })
    .then( data => {

        
        if(data.Missing) {

            error_msg = document.getElementById('form-error-msg');
            error_msg.innerHTML = 'Required: ' + data.Missing;


        }
        else {
            error_msg.innerHTML = " ";
            window.location.href = '/dashboard.php';
        }

    })
    .catch(error => {

        error_msg = document.getElementById('form-error-msg');
        error_msg.innerHTML = error;

    });

});
const validateInput = (email, password) => {

    let clientError = false;

    // Grab the client slide vaidation error message div's by id
    const email_client_error      = document.getElementById('email-error');
    const password_client_error   = document.getElementById('password-error');

    // Email validation

    const emailPattern = /(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/i;

    if(!emailPattern.test(email)) {

        // Set the error message
        email_client_error.innerHTML = 'Invalid email format *';

        // Remove the hidden class
        email_client_error.classList.remove('hidden');

        clientError = true;

    }
    else {

        // Set the hidden class
        email_client_error.classList.add('hidden');
    }


    if(password === '') {

        password_client_error.innerHTML = 'Password required';

        password_client_error.classList.remove('hidden');

        clientError = true;

    }
    else {

        password_client_error.classList.add('hidden');

    }

    return clientError;

};

// Get the html elements needed by id
const form     = document.getElementById('login-form');
const errorBox = document.getElementById('error-box');

// Listen for a login form submit
form.addEventListener('submit', async (event) => {

    // Prevent the default page reload
    event.preventDefault();

    // Grab the form fields needed for login
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    // Client side form validation
    if(validateInput(email, password) === true) {

        return;

    }

    // Prepare the request body
    const body = {email, password};

    try {

        const response = await fetch('/api/login.php', {

            method: 'POST',
            headers: {
                'Content-Type':'application/json'
            },
            body: JSON.stringify(body)

        });

        if(!response.ok) {

            const errorData = await response.json();
            throw new Error(errorData.message);
        }

        // Not needed unless you want to use the success message
        //const responseMessage = await response.json();

        window.location.href = 'contacts.php'

    }
    catch(error) {

        // set the error message in the error box
        errorBox.innerHTML = error;

        // remove the hidden class
        errorBox.classList.remove('hidden');

    }

});
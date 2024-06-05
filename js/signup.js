const validateInput = (first_name, last_name, email, password, confirm_password) => {

    let clientError = false;

    // Grab the client slide vaidation error message div's by id
    const first_name_client_error = document.getElementById('first-name-error');
    const last_name_client_error  = document.getElementById('last-name-error');
    const email_client_error      = document.getElementById('email-error');
    const password_client_error   = document.getElementById('password-error');
    const confirm_password_error  = document.getElementById('confirm-password-error');

    // First name validation
    if(first_name === '') {

        // Set the error message
        first_name_client_error.innerHTML = 'First Name Required *';

        // Remove the hidden class
        first_name_client_error.classList.remove('hidden');

        clientError = true;

    } 
    else{
        // Set the hidden class
        first_name_client_error.classList.add('hidden');
    }

    // Last Name validation 
    if(last_name === '') {

        // Set the error message
        last_name_client_error.innerHTML = 'Last Name Required *';

        // Remove the hidden class
        last_name_client_error.classList.remove('hidden');

        clientError = true;

    } 
    else{
        // Set the hidden class
        last_name_client_error.classList.add('hidden');
    }

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

    // Password validation
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if(!passwordPattern.test(password)) {

        password_client_error.innerHTML = 'Password weak. *';

        password_client_error.classList.remove('hidden');

        clientError = true;

    }
    else {
        
        password_client_error.classList.add('hidden');

    }

    // Confirm password validation
    if(confirm_password !== password || confirm_password === '') {

        confirm_password_error.innerHTML = 'Passwords do not match*';

        confirm_password_error.classList.remove('hidden');

        clientError = true;

    }
    else {

        confirm_password_error.classList.add('hidden');

    }


    return clientError;

};

// Get the html elements by id needed for api call

const form       = document.getElementById('signup-form');
const errorBox   = document.getElementById('error-box');
const successBox = document.getElementById('success-box');


// Add event listener for a from submit
form.addEventListener('submit', async (event) => {

    // Prevent the page reload on submit
    event.preventDefault();

    // Grab the fields needed to sign user up from the form
    const first_name       = document.getElementById('first-name').value.trim();
    const last_name        = document.getElementById('last-name').value.trim();
    const email            = document.getElementById('email').value.trim();
    const password         = document.getElementById('password').value.trim();
    const confirm_password = document.getElementById('confirm-password').value.trim();

    // Client side form validation
    if(validateInput(first_name, last_name, email, password, confirm_password) === true) {

        return;

    }

    // Prepare the request body
    const body = {first_name, last_name, email, password, confirm_password};

    try {
        
        const response = await fetch('/api/register.php', {

            method: 'POST',
            headers: {
                'Content-Type':'application/json'
            },
            body: JSON.stringify(body)            

        });

        if(!response.ok) {
            
            const errorData = await response.json();
            throw new Error(errorData.error);

        }

        const responseMessage = await response.json();

        // ensure the error box is not showing
        if (!errorBox.classList.contains('hidden')) {
            errorBox.classList.add('hidden');
        }

        // set the success message
        successBox.innerHTML = responseMessage.message;

        // remove the hidden class
        successBox.classList.remove('hidden');

        setTimeout(() => {
            window.location.href = '/login.html';
        }, 1000);

    }
    catch(error) {

        // ensure that the success box is not showing
        if (!successBox.classList.contains('hidden')) {
            successBox.classList.add('hidden');
        }

        // set the error message inside the error box
        errorBox.innerHTML = error;

        // remove the hidden class
        errorBox.classList.remove('hidden');

    }

});
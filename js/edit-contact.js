const validateInput = (first_name, last_name, email, phone) => {

    let clientError = false;

    // Grab the client slide vaidation error message div's by id
    const first_name_client_error = document.getElementById('first-name-error');
    const last_name_client_error  = document.getElementById('last-name-error');
    const email_client_error      = document.getElementById('email-error');
    const phone_client_error      = document.getElementById('phone-error');

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

    // Phone validation

    const phonePattern = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;

    if(!phonePattern.test(phone)) {

        phone_client_error.innerHTML = 'Invalid phone format. Must be XXX-XXX-XXXX';

        phone_client_error.classList.remove('hidden');

        clientError = true;

    }
    else {

        phone_client_error.classList.add('hidden');

    }


    return clientError;

};

// Fill in the input fields with the url parameters
document.getElementById('first-name').value = new URLSearchParams(window.location.search).get('first_name');
document.getElementById('last-name').value = new URLSearchParams(window.location.search).get('last_name');
document.getElementById('phone-number').value = new URLSearchParams(window.location.search).get('phone');
document.getElementById('email').value = new URLSearchParams(window.location.search).get('email');

// Get the contact id
const contact_id       = new URLSearchParams(window.location.search).get('id');

// Get the edit contact form by id
const form             = document.getElementById('edit-contact-form');
const errorBox         = document.getElementById('error-box');
const successBox       = document.getElementById('success-box');

// Listen for form submit
form.addEventListener('submit', async (event) => {

    // Prevent the default refresh
    event.preventDefault();

    // Grab form fields needed to create a new contact
    const first_name   = document.getElementById('first-name').value.trim();
    const last_name    = document.getElementById('last-name').value.trim();
    const phone        = document.getElementById('phone-number').value.trim();
    const email        = document.getElementById('email').value.trim();

    // Client side form validation
    if(validateInput(first_name, last_name, email, phone) === true) {

        return;

    }

    // Prepare the request body
    const body         = {first_name, last_name, phone, email, contact_id};

    try {

        const response = await fetch('/api/edit-contact.php', {
            
            method: 'PUT',
            headers: {
                'Content-Type':'application/json'
            },
            body: JSON.stringify(body)

        });

        if(!response.ok) {

            const errorData = await response.json();
            throw new Error(errorData.message);

        }

        const responseMessage = await response.json();

        // Ensure the error box is hidden
        if(!errorBox.classList.contains('hidden')) {

            errorBox.classList.add('hidden');

        }

        // Set the success message
        successBox.innerHTML = responseMessage.message;

        // Remove the hidden class from the success box
        successBox.classList.remove('hidden');

        // Redirect back to contact.php page after 1 second
        setTimeout(() => {

            window.location.href = '/contacts.php';

        }, 1500);

    }
    catch(error) {

        // ensure the success box is hidden
        if(!successBox.classList.contains('hidden')) {

            successBox.classList.add('hidden');

        }

        // set the error message inside the box
        errorBox.innerHTML = error;

        // remove the hidden class from the error box
        errorBox.classList.remove('hidden');

    }

});
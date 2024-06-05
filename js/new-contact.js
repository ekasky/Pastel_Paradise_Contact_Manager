// Get the html elements needed to create a new contact
const form       = document.getElementById('new-contact-form');
const errorBox   = document.getElementById('error-box');
const successBox = document.getElementById('success-box');

// Listen for the new-contact form submit
form.addEventListener('submit', async (event) => {

    // Prevent the default form refresh
    event.preventDefault();

    // Grab form fields needed to create a new contact
    const first_name   = document.getElementById('first-name').value;
    const last_name    = document.getElementById('last-name').value;
    const phone        = document.getElementById('phone-number').value;
    const email        = document.getElementById('email').value;

    // Prepare the request body
    const body = {first_name, last_name, phone, email};

    try {

        const response = await fetch('/api/create-contact.php', {
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

        const responseMessage = await response.json();

        // Ensure the error box is hidden
        if(!errorBox.classList.contains('hidden')) {

            errorBox.classList.add('hidden');

        }

        // Set the success message
        successBox.innerHTML = responseMessage.message;

        // Remove the hidden class from the success box
        successBox.classList.remove('hidden');

        // Redirect back to contact.php page after 1.5 second
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
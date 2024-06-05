// Get the html elements by id needed for api call

const form       = document.getElementById('signup-form');
const errorBox   = document.getElementById('error-box');
const successBox = document.getElementById('success-box');


// Add event listener for a from submit
form.addEventListener('submit', async (event) => {

    // Prevent the page reload on submit
    event.preventDefault();

    // Grab the fields needed to sign user up from the form
    const first_name       = document.getElementById('first-name').value;
    const last_name        = document.getElementById('last-name').value;
    const email            = document.getElementById('email').value;
    const password         = document.getElementById('password').value;
    const confirm_password = document.getElementById('confirm-password').value;

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
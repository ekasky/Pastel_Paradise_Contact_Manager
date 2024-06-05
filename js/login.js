// Get the html elements needed by id
const form     = document.getElementById('login-form');
const errorBox = document.getElementById('error-box');

// Listen for a login form submit
form.addEventListener('submit', async (event) => {

    // Prevent the default page reload
    event.preventDefault();

    // Grab the form fields needed for login
    const email    = document.getElementById('email').value;
    const password = document.getElementById('password').value;

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
// Get login form by id
const form = document.getElementById('login-form');

// Get the error message div
const error_box = document.getElementById('error-message-box');

// Show the successful registration box if coming from register page
const urlSuccessParam = new URLSearchParams(window.location.search);
const success_box = document.getElementById('success-message-box');

if(urlSuccessParam.has('success') && urlSuccessParam.get('success') === 'true') {

    success_box.classList.remove('d-none');
    const newUrl = window.location.href.replace('?success=true', '');
    history.replaceState(null, '', newUrl);

}

// Add event listener for the form submit action
form.addEventListener('submit', (event) => {

    // Prevent the default page refresh
    event.preventDefault();

    // Get the email address and password from the form
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Prepare the request body
    const body = {
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
            success_box.classList.add('d-none');

            return;
        }

        // Use Regex to verfiy email address is in valid form
        const pattern = /^\S+@\S+\.\S+$/;

        if(!pattern.test(email)) {

            error_box.textContent = 'Invalid email address format';
            error_box.classList.remove('d-none');
            success_box.classList.add('d-none');
            return;

        }
         
        // Make redirect request to ensure user is valid
        window.location.href = '/dashboard.php';

    })
    .catch(error => {

        console.log(error);

    });
    

});
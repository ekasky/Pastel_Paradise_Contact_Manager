// Get the login form by id
let form = document.getElementById('login-form');

// Add event listener for form submit
document.addEventListener('submit', (event) => {

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
            throw new Error("Network error");
        }

        return res.json();

    })
    .then( data => {

        console.log(data);

    })
    .catch(error => {

        console.log(error);

    });

});
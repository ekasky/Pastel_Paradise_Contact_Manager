// Get login form by id
let form = document.getElementById('login-form');

// Add event listener for the form submit action
form.addEventListener('submit', (event) => {

    // Prevent the default page refresh
    event.preventDefault();

    // Get the email address and password from the form
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;

    // Use Regex to verfiy email address is in valid form
    const pattern = /^\S+@\S+\.\S+$/;

    if(!pattern.test(email)) {

        console.log("Invalid email");
        return;

    }


    // Prepare the request body
    let body = {
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
            console.log(`ERROR -> ${data.error}`);
            return;
        }
         
        // Make redirect request to ensure user is valid
        window.location.href = '/dashboard.php';

    })
    .catch(error => {

        console.log(error);

    });
    

});
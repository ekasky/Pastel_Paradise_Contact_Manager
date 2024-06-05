// Get the html elements needed to logout user
const logoutBtn       = document.getElementById('logout-btn');
const logoutBtnMobile = document.getElementById('logout-btn-mobile');

// Function to logout user
const logout = (event) => {

    fetch('/api/logout.php', {
        
        method: 'DELETE'
    
    })
    .then(response => {
    
        if(response.status === 404 || response.status === 405 || response.status === 500) {

            return response.json().then(error => {throw error;});

        }
        
        return response.json();
    
    })
    .then(data => {
    
        window.location.href = '/login.html';
    
    })
    .catch(error => {
    
        console.log(error);
    
    });

};

// Add event listener to listen for logout btn click
logoutBtn.addEventListener('click', logout);
logoutBtnMobile.addEventListener('click', logout);


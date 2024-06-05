
const renderCards = (contacts) => {

    const container       = document.getElementById('contact-container');       // grab the contact container from the html

    // Remove all the existing contacts
    container.innerHTML = '';

    // loop through all the contacts and dynamically create a contact card
    contacts.forEach(contact => {

        const card = `
        
        <div id="${contact.id}" class="bg-white rounded-lg shadow-md p-6 flex flex-col justify-between">
            <div>
                <h1 class="text-lg font-semibold mb-2">${contact.first_name} ${contact.last_name}</h1>
                <p class="text-gray-600 mb-2">Phone: ${contact.phone}</p>
                <p class="text-gray-600 mb-2">Email: ${contact.email}</p>
            </div>
            <div class="flex justify-end">
                <a href="/edit-contact.php?id=${contact.id}&first_name=${contact.first_name}&last_name=${contact.last_name}&phone=${contact.phone}&email=${contact.email}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded delete-btn">Delete</button>
            </div>
        </div>

        `;

        container.insertAdjacentHTML('beforeend', card);                    // Insert the card into the container

    });

    // Get every contact delete button
    const deleteBtns = document.querySelectorAll('.delete-btn');

    // Add event listeners for all the delete buttons in the contacts. These are used to connect each contact card to the backend api
    deleteBtns.forEach(btn => {

        btn.addEventListener('click', () => {

            const id = btn.parentNode.parentNode.id;
            delete_contact(id);

        });

    });

};

const delete_contact = async (id) => {

    try {

        const response = await fetch(`/api/delete-contact.php/${id}`, {
            method: 'DELETE'
        });
    
        if(!response.ok) {
    
            const errorData = await response.json();
            throw new Error(errorData.message);
    
        }

        // Only needed if your want to use the response message
        //const responseMessage = await response.json();

        const contact = document.getElementById(id);
        contact.remove();
        console.log("Removed");



    }
    catch(error) {

        console.log(error);

    }

};

const get_contacts = async () => {

    try {

        const response = await fetch('/api/get-contacts.php');

        if(!response.ok) {

            const errorData = await response.json();
            throw new Error(errorData.message);

        }

        const responseMessage = await response.json();
        const contacts        = responseMessage.message;                            // Get the contacts from the request

        renderCards(contacts);



    }
    catch(error) {

    }
    

};

const search = async () => {

    const searchInput = document.getElementById('search');

    searchInput.addEventListener('input', async (event) => {

        try {

            const searchPattern = event.target.value;

            const response = await fetch('/api/search.php', {
                
                method: 'POST',
                headers: {
                    'Content-Type':'application/json'
                },
                body: JSON.stringify({search: searchPattern})

            });

            if(!response.ok) {

                const errorData = await response.json();
                throw new Error(errorData.message);

            }

            const responseMessage = await response.json();
            const contacts       = responseMessage.message;

            renderCards(contacts);


        }
        catch(error) {

            console.log(error);

        }

    });

};

get_contacts();
search();

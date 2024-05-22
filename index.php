<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1>Contact Manager</h1>

    <button id="btn">Login</button>

    <script>

        let btn = document.getElementById('btn');

        btn.addEventListener('click', () => {

            const data = {
                username: 'username',
                password: 'password'
            };

            fetch("/api/login.php", {
                method: "POST",
                headers: {
                    'Content-Type':'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => {

                if(!res.ok) {
                    throw new Error("Network error");
                }

                // Extract JWT from response
                return res.json();

            })
            .then(data => {
                // Store JWT in a cookie with expiration time
                document.cookie = `jwt=${data.token}; expires=${new Date(data.expires)}`;
                console.log("JWT stored as cookie:", data.token);
            })
            .catch(err => {
                console.log('Error:', err);
            });

        });

    </script>

</body>
</html>

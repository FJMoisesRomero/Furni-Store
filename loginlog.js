document.getElementById('login-btn').addEventListener('click', function () {
    // Obtener valores de los campos
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    // Validar que todos los campos estén llenos
    if (!email || !password) {
        alert('Por favor, complete todos los campos.');
        return;
    }

    // Validar formato de email
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Por favor, ingrese un email válido.');
        return;
    }

    // Enviar datos al servidor usando fetch
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'email': email,
            'password': password
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Iniciaste sesión correctamente.');
                window.location.href = 'index.php';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

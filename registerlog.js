//Validacion de datos REGISTRO

document.getElementById('register-btn').addEventListener('click', function () {
    // Obtener valores de los campos
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm-password').value;
    var termsAccepted = document.getElementById('cbx-46').checked;

    // Validar que todos los campos estén llenos
    if (!username || !email || !password || !confirmPassword) {
        alert('Por favor, complete todos los campos.');
        return;
    }

    // Validar formato de email
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Por favor, ingrese un email válido.');
        return;
    }

    // Validar que las contraseñas coincidan
    if (password !== confirmPassword) {
        alert('Las contraseñas no coinciden.');
        return;
    }

    // Validar que se acepten los términos y condiciones
    if (!termsAccepted) {
        alert('Debe aceptar los términos y condiciones.');
        return;
    }

    // Si todo es válido, enviar el formulario
    alert('Te has registrado correctamente.');
    document.getElementById('registration-form').submit();
});
// register.js - Validación del formulario de registro

// Obtiene el formulario y el elemento donde mostraremos el error
const registerForm = document.getElementById('registerForm');
const mensajeError = document.getElementById('mensajeError');

// Función principal que se ejecuta al enviar el formulario
registerForm.addEventListener('submit', function(event) {
    
    // Obtiene los valores de los campos
    const nameInput = document.getElementById('name').value.trim();
    const emailInput = document.getElementById('email').value.trim();
    const passwordInput = document.getElementById('password').value;
    const passwordConfirmationInput = document.getElementById('password_confirmation').value;

    // Oculta el mensaje de error anterior si existe
    if (mensajeError) {
        mensajeError.style.display = 'none';
        mensajeError.textContent = '';
    }

    // Validar que los campos no estén vacíos
    if (nameInput === "" || emailInput === "" || passwordInput === "" || passwordConfirmationInput === "") {
        event.preventDefault();
        if (mensajeError) {
            mensajeError.textContent = "Por favor, completa todos los campos.";
            mensajeError.style.display = 'block';
        }
        return false; 
    }

    // Validar formato de email básico
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailInput)) {
        event.preventDefault();
        if (mensajeError) {
            mensajeError.textContent = "Por favor, ingresa un correo electrónico válido.";
            mensajeError.style.display = 'block';
        }
        return false;
    }

    // Validar que las contraseñas coincidan
    if (passwordInput !== passwordConfirmationInput) {
        event.preventDefault();
        if (mensajeError) {
            mensajeError.textContent = "Las contraseñas no coinciden.";
            mensajeError.style.display = 'block';
        }
        return false;
    }

    // Validar longitud mínima de contraseña (Laravel requiere mínimo 8 caracteres por defecto)
    if (passwordInput.length < 8) {
        event.preventDefault();
        if (mensajeError) {
            mensajeError.textContent = "La contraseña debe tener al menos 8 caracteres.";
            mensajeError.style.display = 'block';
        }
        return false;
    }

    // Si todo está bien, el formulario se enviará normalmente a Laravel
    // Laravel se encargará de la validación y registro
    return true;
});


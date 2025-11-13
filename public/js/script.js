/* // script.js

// Obtiene el formulario por su ID
const loginForm = document.getElementById('loginForm');

// Credenciales válidas predefinidas (SOLO PARA PRUEBA LOCAL)
const USUARIO_VALIDO = "admin";
const CONTRASENA_VALIDA = "clave123";

// Agrega un "escuchador" de eventos para cuando se intenta enviar el formulario
loginForm.addEventListener('submit', function(event) {
    // 1. Evita que el formulario se envíe de forma predeterminada (lo que recargaría la página)
    event.preventDefault();

    // 2. Obtiene los valores ingresados por el usuario
    const usuarioInput = document.getElementById('usuario').value;
    const contrasenaInput = document.getElementById('contrasena').value;

    // 3. Compara los valores ingresados con las credenciales válidas
    if (usuarioInput === USUARIO_VALIDO && contrasenaInput === CONTRASENA_VALIDA) {
        
        // 4. AUTENTICACIÓN EXITOSA: Redirige a la página de bienvenida
        alert("¡Inicio de sesión exitoso!");
        window.location.href = "dashboard.html"; 
        
    } else {
        
        // 5. AUTENTICACIÓN FALLIDA: Muestra un mensaje de error
        alert("Error de inicio de sesión: Usuario o Contraseña incorrectos.");
        
        // Opcional: Limpiar los campos de contraseña después de un fallo
        document.getElementById('contrasena').value = "";
    }
}); */
// script.js - Integrado con autenticación real de Laravel/Breeze

// Obtiene el formulario y el elemento donde mostraremos el error
const loginForm = document.getElementById('loginForm');
const mensajeError = document.getElementById('mensajeError');

// Función principal que se ejecuta al enviar el formulario
loginForm.addEventListener('submit', function(event) {
    
    // Obtiene los valores de los campos, eliminando espacios en blanco (trim)
    const emailInput = document.getElementById('email').value.trim();
    const passwordInput = document.getElementById('password').value;

    // Oculta el mensaje de error anterior si existe
    if (mensajeError) {
        mensajeError.style.display = 'none';
        mensajeError.textContent = '';
    }

    // Validar que los campos no estén vacíos
    if (emailInput === "" || passwordInput === "") {
        event.preventDefault(); // Evita el envío si hay campos vacíos
        if (mensajeError) {
            mensajeError.textContent = "Por favor, ingresa tu correo electrónico y contraseña.";
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

    // Si todo está bien, el formulario se enviará normalmente a Laravel
    // Laravel se encargará de la autenticación y mostrará errores en caso sea  necesario
    return true;
});
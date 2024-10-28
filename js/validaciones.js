document.addEventListener("DOMContentLoaded", function () {
    // Almacenamos el formulario de registro en una variable llamada registerForm
    const registerForm = document.querySelector(".register-container form");

    // Función para mostrar un mensaje de error
    function showError(element, message) {
        // Crear un div para el mensaje de error
        let errorElement = document.getElementById(`${element.name}-error`);
        
        // Si no existe, lo creamos
        if (!errorElement) {
            errorElement = document.createElement("div");
            errorElement.id = `${element.name}-error`;
            errorElement.className = "error-message";
            element.parentNode.insertBefore(errorElement, element.nextSibling);
        }
        
        // Establecemos el mensaje de error
        errorElement.textContent = message;
    }

    // Función para limpiar los mensajes de error
    function clearErrors(element) {
        // Buscar el mensaje de error por ID
        const errorElement = document.getElementById(`${element.name}-error`);
        
        // Si existe, eliminarlo
        if (errorElement) {
            errorElement.remove();
        }
    }

    // Funciones de validación individuales
    function validateUsername(element) {
        clearErrors(element);
        if (element.value.trim() === "") {
            showError(element, "El nombre de usuario es obligatorio.");
            return false; 
        }

        if (element.value.length > 20) {
            showError(element, "El nombre de usuario no puede contener más de 20 caracteres.");
            return false;
        }

        return true;
    }

    function validateEmail(element) {
        clearErrors(element);
        if (element.value.trim() === "") {
            showError(element, "El correo electrónico es obligatorio.");
            return false; // Indicar que hay un error
        }

        if (!element.value.includes("@")) {
            showError(element, "El correo debe contener un '@'.");
            return false;
        }

        return true;
    }

    function validatePassword(element) {
        clearErrors(element);
        if (element.value.trim() === "") {
            showError(element, "La contraseña es obligatoria.");
            return false;
        }
        
        if (element.value.length < 6) {
            showError(element, "La contraseña debe tener al menos 6 caracteres.");
            return false;
        }

        if (!/^[a-zA-Z0-9]+$/.test(element.value)) {
            showError(element, "La contraseña no debe incluir caracteres especiales.");
            return false;
        }

        if (!/[a-zA-Z]/.test(element.value) || !/[0-9]/.test(element.value)) {
            showError(element, "La contraseña debe incluir al menos una letra y un número.");
            return false;
        }

        return true; // Validación exitosa
    }

    // Función para validar el formulario al enviar
    function validateRegisterForm(event) {
        let valid = true;

        // Validar todos los campos y almacenar si hay errores
        const usernameInput = registerForm.querySelector("input[name='nombre_user']");
        const emailInput = registerForm.querySelector("input[name='correo_user']");
        const passwordInput = registerForm.querySelector("input[name='contrasena']");

        valid &= validateUsername(usernameInput);
        valid &= validateEmail(emailInput);
        valid &= validatePassword(passwordInput);

        // Si hay errores, evitar el envío del formulario
        if (!valid) {
            event.preventDefault();
        }
    }

    // Asignación de eventos onblur para cada campo
    const usernameInput = registerForm.querySelector("input[name='nombre_user']");
    const emailInput = registerForm.querySelector("input[name='correo_user']");
    const passwordInput = registerForm.querySelector("input[name='contrasena']");

    usernameInput.onblur = function () { validateUsername(this); };
    emailInput.onblur = function () { validateEmail(this); };
    passwordInput.onblur = function () { validatePassword(this); };

    // Agregar el evento submit para validar antes de enviar
    registerForm.addEventListener("submit", validateRegisterForm);
});

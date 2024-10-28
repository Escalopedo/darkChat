document.addEventListener("DOMContentLoaded", function () {
    // Selecciona el formulario de registro dentro del contenedor con la clase "register-container"
    const registerForm = document.querySelector(".register-container form");

    // Función para mostrar un mensaje de error debajo del campo correspondiente
    function showError(element, message) {
        // Selecciona el elemento de error inmediatamente después del campo (si ya existe)
        let errorElement = element.nextElementSibling;
        // Si no hay un mensaje de error o no tiene la clase "error-message", crea un nuevo div para el mensaje de error
        if (!errorElement || !errorElement.classList.contains("error-message")) {
            errorElement = document.createElement("div");
            errorElement.classList.add("error-message");
            element.parentNode.insertBefore(errorElement, element.nextSibling);
        }
        // Asignamos el mensaje de error proporcionado como texto del elemento de error
        errorElement.textContent = message;
    }

    // Función para limpiar los mensajes de error
    function clearErrors(form) {
        const errorMessages = form.querySelectorAll(".error-message");
        errorMessages.forEach(msg => msg.remove());
    }

    // Función para validar el formulario de registro
    function validateRegisterForm(event) {
        clearErrors(registerForm);

        const username = registerForm.querySelector("input[name='nombre_user']");
        const email = registerForm.querySelector("input[name='correo_user']");
        const password = registerForm.querySelector("input[name='contrasena']");
        let valid = true;

        // Validación del nombre de usuario
        // No se puede dejar el campo vacio
        if (username.value.trim() === "") {
            showError(username, "El nombre de usuario es obligatorio.");
            valid = false;
        }

        // No puede contener mas de 20 caracteres
        if (username.value.length>20) {
            showError(username, "El nombre de usuario no puede contener mas de 20 caracteres.");
            valid = false;
        }

        // Validación de correo
        // No se puede dejar el campo vacio
        if (email.value.trim() === "") {
            showError(email, "El correo electrónico es obligatorio.");
            valid = false;
        }

        if (!email.value.includes("@")) {
            showError(email, "El correo debe contener un '@'.");
            valid = false;
        }

        // Validación de contraseña
        // No se puede dejar el campo vacio
        if (password.value.trim() === "") {
            showError(password, "La contraseña es obligatoria.");
            valid = false;
        }

        // Debe contener mas de 6 caracteres
        if (password.value.length > 0 && password.value.length < 6) {
            showError(password, "La contraseña debe tener al menos 6 caracteres.");
            valid = false;
        }

        // No se puede incluir caracteres especiales
        if (password.value.length >= 6 && !/^[a-zA-Z0-9]+$/.test(password.value)) {
            showError(password, "La contraseña no debe incluir caracteres especiales.");
            valid = false;
        }

        // La contraseña debe contener al menos una letra y un número
        if (password.value.length >= 6 && (!/[a-zA-Z]/.test(password.value) || !/[0-9]/.test(password.value))) {
            showError(password, "La contraseña debe incluir al menos una letra y un número.");
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    }

    // Agrega el event listener para validar al enviar el formulario de registro
    registerForm.addEventListener("submit", validateRegisterForm);
});

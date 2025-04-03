// Función para verificar el login
function verificarLogin() {
    const documento = document.getElementById("username").value; // Captura el documento
    const password = document.getElementById("password").value; // Captura la contraseña
    const role = document.getElementById("role").value; // Captura el rol seleccionado
    const mensaje = document.getElementById("message");

    // Verifica que los campos no estén vacíos
    if (!documento || !password || !role) {
        mensaje.style.color = "red";
        mensaje.textContent = "Por favor, complete todos los campos.";
        return;
    }

    fetch("../../controllers/php/login.php", { // Asegúrate de que esta URL sea correcta
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            documento: documento, // Envía el documento
            password: password, // Envía la contraseña
            role: role // Envía el rol seleccionado
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            mensaje.style.color = "blue";
            mensaje.textContent = data.message;
            setTimeout(() => {
                window.location.href = data.redirect; // Redirigir según el rol
            }, 1000);
        } else {
            mensaje.style.color = "red";
            mensaje.textContent = data.message;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mensaje.style.color = "red";
        mensaje.textContent = "Error de conexión. Por favor, intenta más tarde.";
    });
}

// Agregar el código para manejar los botones de cambio entre paneles
document.addEventListener('DOMContentLoaded', function() {
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
});

// Modificar la función de registro para manejar mejor los errores
document.getElementById("signupForm").addEventListener("submit", async function (event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const signupMessage = document.getElementById("signupMessage");

    // Validación de mayor de edad y tipo de documento
    const fechaNacimiento = new Date(formData.get("fecha_nacimiento"));
    const tipoDocumento = formData.get("tipo_documento");
    const hoy = new Date();
    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear(); // Cambiado de const a let
    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
    const dia = hoy.getDate() - fechaNacimiento.getDate();

    if (mes < 0 || (mes === 0 && dia < 0)) {
        edad--;
    }

    if (edad >= 18) {
        if (tipoDocumento === "Tarjeta de Identidad") {
            alert("Eres mayor de edad. Debes seleccionar 'Cédula' o 'Pasaporte'.");
            return;
        }
    } else {
        if (tipoDocumento !== "Tarjeta de Identidad") {
            alert("Eres menor de edad. Debes seleccionar 'Tarjeta de Identidad'.");
            return;
        }
    }

    try {
        const response = await fetch("../../controllers/php/registro.php", {
            method: "POST",
            body: formData
        });

        // Verificar si la respuesta es válida
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const result = await response.json();

        signupMessage.style.color = result.success ? "green" : "red";
        signupMessage.textContent = result.message;

        if (result.success) {
            // Limpiar el formulario
            event.target.reset();
            setTimeout(() => {
                signupMessage.textContent = ""; // Ocultar el mensaje después de 6 segundos
                container.classList.remove("right-panel-active");
            }, 1000); 
        }
    } catch (error) {
        console.error('Error:', error);
        signupMessage.style.color = "red";
        signupMessage.textContent = "Error de conexión. Por favor, intenta más tarde.";
    }
});
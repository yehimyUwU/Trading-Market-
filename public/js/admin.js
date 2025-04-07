// Agregar clase a los elementos en hover (para la barra de navegación)
let list = document.querySelectorAll(".navigation li");

function activeLink() {
    list.forEach((item) => {
        item.classList.remove("hovered");
    });
    this.classList.add("hovered");
}

list.forEach(item => item.addEventListener("mouseover", activeLink));

// Función para alternar el menú plegable
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
    navigation.classList.toggle("active");
    main.classList.toggle("active");
};

// Manejo del modal para el perfil
const profileImage = document.getElementById("profileImage"); // Imagen de perfil (si está en otra vista)
const profileModal = document.getElementById("profileModal");
const closeModal = document.querySelector(".close");

// Oculta el modal al cargar la página
window.addEventListener("DOMContentLoaded", () => {
  profileModal.style.display = "none"; // Asegura que el modal esté oculto inicialmente
});

// Muestra el modal al hacer clic en la imagen de perfil
profileImage?.addEventListener("click", () => {
  profileModal.style.display = "flex"; // Cambia a "flex" para que el CSS con flexbox funcione
});

// Cierra el modal al hacer clic en la "X"
closeModal.addEventListener("click", () => {
  profileModal.style.display = "none";
});

// Cierra el modal al hacer clic fuera del contenido del modal
window.addEventListener("click", (event) => {
  if (event.target === profileModal) {
    profileModal.style.display = "none";
  }
});

// Funciones para editar y guardar los datos del perfil
const editButton = document.getElementById("editButton"); // Botón Editar
const saveButton = document.getElementById("saveButton"); // Botón Guardar
const inputs = document.querySelectorAll("#profileForm input");
const profileForm = document.getElementById("profileForm");

// Habilitar los inputs al hacer clic en el botón Editar
editButton.addEventListener("click", (event) => {
    event.preventDefault(); // Evita cualquier acción predeterminada
    inputs.forEach(input => {
        input.disabled = false; // Habilita todos los inputs
    });
});

// Guardar datos del perfil al hacer clic en Guardar
saveButton.addEventListener("click", (event) => {
    event.preventDefault(); // Evita la recarga del formulario

    const formData = new FormData(profileForm); // Captura los datos del formulario
    formData.append("action", "actualizarPerfil"); // Envía la acción al controlador

    fetch('../../controllers/php/controlador_usuario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            inputs.forEach(input => {
                input.disabled = true; // Bloquea nuevamente los inputs
            });
        } else {
            alert('Error al actualizar: ' + data.message);
        }
    })
    .catch(error => console.error('Error en la actualización:', error));
});

// Función para cargar datos del usuario en los inputs
function loadUserData() {
  fetch('../../controllers/php/obtener_perfil_admin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: "obtenerPerfil" })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const user = data.data;
            // Rellena los inputs del modal con los datos del usuario
            document.getElementById('name').value = user.nombre;
            document.getElementById('lastname').value = user.apellido;
            document.getElementById('document').value = user.documento;
            document.getElementById('email').value = user.email;
            document.getElementById('birthdate').value = user.fecha_nacimiento;
            document.getElementById('gender').value = user.genero;
        } else {
            console.error(data.message);
        }
    })
    .catch(error => console.error('Error al cargar los datos del usuario:', error));
}

// Llama a la función cuando se cargue la página
document.addEventListener('DOMContentLoaded', loadUserData);

// Función para cerrar sesión
function logout() {
  fetch('../../controllers/php/logout_admin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: "logout" })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '../../views/html/longin.html'; // Redirige al HTML de login
        } else {
            alert('Error al cerrar sesión: ' + data.message);
        }
    })
    .catch(error => console.error('Error en el logout:', error));
}

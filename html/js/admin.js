// Agregar clase a los hover//
let list = document.querySelectorAll(".navigation li");

function activeLink(){
    list.forEach((item) => {
        item.classList.remove("hovered");
    });
    this.classList.add("hovered");
}

list.forEach(item => item.addEventListener("mouseover", activeLink))

//Menu plegable para el modal
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function(){
    navigation.classList.toggle("active");
    main.classList.toggle("active");
};

// Obtén los elementos del DOM
const profileImage = document.getElementById("profileImage");
const profileModal = document.getElementById("profileModal");
const closeModal = document.querySelector(".close");

// Oculta el modal al cargar la página
window.addEventListener("DOMContentLoaded", () => {
  profileModal.style.display = "none"; // Asegura que el modal esté oculto al iniciar
});

// Muestra el modal al hacer clic en la imagen
profileImage.addEventListener("click", () => {
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


// Obtén los elementos con sus nuevos IDs
const editButton = document.getElementById("editButton"); // Botón Editar
const saveButton = document.getElementById("saveButton"); // Botón Guardar
const inputs = document.querySelectorAll("#profileForm input");

// Habilitar los inputs al hacer clic en el botón Editar
editButton.addEventListener("click", (event) => {
  event.preventDefault(); // Evita cualquier acción predeterminada
  inputs.forEach(input => {
    input.disabled = false; // Habilita todos los inputs
  });
});

// Deshabilitar los inputs al hacer clic en el botón Guardar
saveButton.addEventListener("click", (event) => {
  event.preventDefault(); // Evita la recarga del formulario
  inputs.forEach(input => {
    input.disabled = true; // Bloquea nuevamente los inputs
  });
});

// Script para mensajes_admin y poder responder mensajes

function responderMensaje(nombre, correo) {
  document.getElementById('nombre').value = nombre;
  document.getElementById('correo').value = correo;
  document.getElementById('respuesta').focus();
}

document.getElementById('respuesta-form').addEventListener('submit', function(e) {
  e.preventDefault();
  alert('Mensaje enviado correctamente.');
});


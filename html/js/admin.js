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

  const formData = new FormData(document.getElementById("profileForm")); // Captura los datos del formulario

  fetch('../php/actualizar_perfil_admin.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Datos actualizados exitosamente');
        inputs.forEach(input => {
          input.disabled = true; // Bloquea nuevamente los inputs
        });
      } else {
        alert('Error al actualizar: ' + data.message);
      }
    })
    .catch(error => console.error('Error en la actualización:', error));
});


function logout() {
  fetch('../php/logout.php', { method: 'POST' })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              window.location.href = '../html/longin.html'; // Redirige al HTML de login
          } else {
              alert('Error al cerrar sesión: ' + data.message);
          }
      })
      .catch(error => console.error('Error en el logout:', error));
}



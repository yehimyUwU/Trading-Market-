// Obtener elementos del DOM
const openChatbot = document.getElementById("openChatbot");
const chatbotModal = document.getElementById("chatbotModal");
const closeModal = document.querySelector(".close");
const sendMessage = document.getElementById("sendMessage");
const chatbotMessages = document.getElementById("chatbotMessages");
const chatbotInput = document.getElementById("chatbotInput");

// Mensaje de bienvenida al abrir el modal
openChatbot.addEventListener("click", () => {
  chatbotModal.style.display = "flex";

  // Agregar mensaje de bienvenida si aún no existe
  if (!document.querySelector(".welcome-message")) {
    const welcomeMessage = document.createElement("div");
    welcomeMessage.className = "welcome-message";
    welcomeMessage.textContent = "TradiBot: ¡Bienvenido! Para saber que preguntar escribe: preguntas'.";
    chatbotMessages.appendChild(welcomeMessage);
  }
});

// Cerrar el modal
closeModal.addEventListener("click", () => {
  chatbotModal.style.display = "none";
});

// Cerrar el modal si haces clic fuera de él
window.addEventListener("click", (event) => {
  if (event.target === chatbotModal) {
    chatbotModal.style.display = "none";
  }
});

// Enviar mensaje al chatbot
sendMessage.addEventListener("click", () => {
  const userMessage = chatbotInput.value.trim();

  if (userMessage) {
    // Mostrar el mensaje del usuario
    const userBubble = document.createElement("div");
    userBubble.className = "user-message"; // Clase de estilo para el usuario
    userBubble.textContent = userMessage;
    chatbotMessages.appendChild(userBubble);

    // Enviar la pregunta al servidor PHP
    fetch('../../controllers/php/controlador_chatbot.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `pregunta=${encodeURIComponent(userMessage)}`,
    })
      .then((response) => response.text())
      .then((botResponse) => {
        // Mostrar la respuesta del chatbot
        const botBubble = document.createElement("div");
        botBubble.className = "bot-message"; // Clase de estilo para el bot
        botBubble.textContent = botResponse;
        chatbotMessages.appendChild(botBubble);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight; // Desplazar hacia abajo
      })
      .catch((error) => {
        console.error('Error:', error);

        // Mensaje de error en caso de fallo
        const errorBubble = document.createElement("div");
        errorBubble.className = "bot-message";
        errorBubble.textContent = "Chatbot: Hubo un error al procesar tu pregunta. Por favor, intenta nuevamente.";
        chatbotMessages.appendChild(errorBubble);
      });


      document.getElementById("sendMessage").addEventListener("click", function() {
        const userInput = document.getElementById("chatbotInput").value;
        const chatMessages = document.getElementById("chatbotMessages");
    
        fetch('../../controllers/php/controlador_chatbot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'pregunta=' + encodeURIComponent(userInput)
        })
        .then(response => response.text())
        .then(responseText => {
            // Mostrar respuesta en el chatbot
            const userMessage = document.createElement("div");
            userMessage.textContent = "Tú: " + userInput;
            chatMessages.appendChild(userMessage);
    
            const botMessage = document.createElement("div");
            botMessage.textContent = "Bot: " + responseText;
            chatMessages.appendChild(botMessage);
    
            // Limpiar el input
            document.getElementById("chatbotInput").value = "";
        })
        .catch(error => console.error("Error:", error));
    });
    // Limpiar entrada
    chatbotInput.value = "";
  }
});

document.addEventListener("DOMContentLoaded", function() {
  const tablaSolicitudes = document.getElementById("tablaSolicitudes").querySelector("tbody");

  // Hacer una solicitud al servidor para obtener las solicitudes
  fetch('../../controllers/php/controlador_solicitud.php') // Cambia esta ruta según tu estructura
      .then(response => response.json())
      .then(data => {
          tablaSolicitudes.innerHTML = ""; // Limpiar la tabla antes de agregar datos

          // Iterar sobre cada solicitud y agregarla a la tabla
          data.forEach(solicitud => {
              const fila = document.createElement("tr");
              fila.innerHTML = `
                  <td>${solicitud.nombre}</td>
                  <td>${solicitud.email}</td>
                  <td>${solicitud.mensaje}</td>
                  <td>
                      <button class="btn-responder" onclick="responderMensaje('${solicitud.nombre}', '${solicitud.email}')">
                          Responder
                      </button>
                  </td>
              `;
              tablaSolicitudes.appendChild(fila);
          });
      })
      .catch(error => console.error("Error al cargar solicitudes:", error));
});


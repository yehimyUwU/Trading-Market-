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

    // Limpiar entrada
    chatbotInput.value = "";
  }
});

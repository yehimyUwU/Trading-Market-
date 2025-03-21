// Obtener elementos del DOM
const openChatbot = document.getElementById("openChatbot");
const chatbotModal = document.getElementById("chatbotModal");
const closeModal = document.querySelector(".close");
const sendMessage = document.getElementById("sendMessage");
const chatbotMessages = document.getElementById("chatbotMessages");
const chatbotInput = document.getElementById("chatbotInput");

// Abrir el modal
openChatbot.addEventListener("click", () => {
  chatbotModal.style.display = "flex";
});

// Cerrar el modal
closeModal.addEventListener("click", () => {
  chatbotModal.style.display = "none";
});

// Enviar mensaje al chatbot
sendMessage.addEventListener("click", () => {
  const userMessage = chatbotInput.value.trim();
  if (userMessage) {
    // Mostrar mensaje del usuario
    const userBubble = document.createElement("div");
    userBubble.textContent = `Tú: ${userMessage}`;
    chatbotMessages.appendChild(userBubble);

    // Simular respuesta del chatbot
    const botBubble = document.createElement("div");
    botBubble.textContent = `Chatbot: Efectivamente, me gustan los "${userMessage}".`;
    chatbotMessages.appendChild(botBubble);

    // Limpiar entrada
    chatbotInput.value = "";
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
  }
});

// Cerrar el modal si haces clic fuera de él
window.addEventListener("click", (event) => {
  if (event.target === chatbotModal) {
    chatbotModal.style.display = "none";
  }
});

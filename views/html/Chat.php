<!DOCTYPE html>
<?php
/**
 * Archivo: Chat.php
 * Descripción: Sistema de mensajería entre usuarios
 * Conexiones:
 * - Se conecta con: controllers/php/barra_prove.php (para la barra de navegación)
 * Funcionalidades:
 * - Lista de contactos
 * - Área de chat en tiempo real
 * - Indicadores de estado (en línea/ausente)
 * - Historial de mensajes
 * - Búsqueda de contactos
 */
require '../../controllers/php/barra_prove.php'; 
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Trading Market</title>
    <link rel="stylesheet" href="../../public/Estilos/Admins-provedor.css">
    <link rel="stylesheet" href="../../public/Estilos/Chat.css">
    <link rel="stylesheet" href="../../public/Estilos/prove_estilos.css" />
    <link rel="stylesheet" href="../../public/Estilos/estilos_pedidos.css" />
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
    <section id="content">
        <nav>
            <a href="#" class="nav-link">Chat</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Buscar contacto..." aria-label="Buscar contacto">
                    <button type="submit" class="search-btn" aria-label="Buscar">
                        <i class='bx bx-search'></i>
                    </button>
                </div>
            </form>
            <a href="#" class="notification" aria-label="Notificaciones">
                <i class='bx bxs-bell'></i>
                <span class="num">3</span>
            </a>
        </nav>

        <main>
            <div class="box-info">
                <li>
                    <i class='bx bxs-user'></i>
                    <span class="text">
                        <h3>Contactos</h3>
                        <p>Personas disponibles</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-chat'></i>
                    <span class="text">
                        <h3>Mensajes</h3>
                        <p>Conversaciones activas</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-time'></i>
                    <span class="text">
                        <h3>Recientes</h3>
                        <p>Últimas conversaciones</p>
                    </span>
                </li>
            </div>

            <div class="chat-interface">
                <!-- Lista de contactos -->
                <div class="contacts-list">
                    <div class="head">
                        <h3>Personas disponibles</h3>
                        <i class='bx bx-search' title="Buscar contactos"></i>
                    </div>
                    
                    <div class="contacts">
                        <div class="contact active">
                            <img src="../../public/imag/67f5eac12334e_Nueva Foto Carnet (1).jpg" alt="Cliente 1" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGRjZCMDAiLz4KPHN2ZyB4PSIxMiIgeT0iMTIiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJ3aGl0ZSI+CjxwYXRoIGQ9Ik0xMiAxMmMyLjIxIDAgNC0xLjc5IDQtNHMtMS43OS00LTQtNC00IDEuNzktNCA0IDEuNzkgNCA0IDR6bTAgMmMtMi42NyAwLTggMS4zNC04IDR2MmgxNnYtMmMwLTIuNjYtNS4zMy00LTgtNHoiLz4KPC9zdmc+Cjwvc3ZnPgo='">
                            <div class="contact-info">
                                <span class="name">Cliente 1</span>
                                <span class="status">En línea</span>
                                <span class="last-message">¿Tienes este producto en stock?</span>
                            </div>
                            <span class="badge">3</span>
                        </div>
                        <div class="contact">
                            <img src="../../public/imag/67f60cb79ece3_Nueva Foto Carnet.jpg" alt="Cliente 2" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGRkFFMDAiLz4KPHN2ZyB4PSIxMiIgeT0iMTIiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJ3aGl0ZSI+CjxwYXRoIGQ9Ik0xMiAxMmMyLjIxIDAgNC0xLjc5IDQtNHMtMS43OS00LTQtNC00IDEuNzktNCA0IDEuNzkgNCA0IDR6bTAgMmMtMi42NyAwLTggMS4zNC04IDR2MmgxNnYtMmMwLTIuNjYtNS4zMy00LTgtNHoiLz4KPC9zdmc+Cjwvc3ZnPgo='">
                            <div class="contact-info">
                                <span class="name">Cliente 2</span>
                                <span class="status">Ausente</span>
                                <span class="last-message">Gracias por la ayuda</span>
                            </div>
                        </div>
                        <div class="contact">
                            <img src="../../public/imag/67f60d12052d2_1710343333Z13YH6fe7d4c00ca741a583a9287763eaac5eS.jpg" alt="Administrador" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiM2Yzc1N2QiLz4KPHN2ZyB4PSIxMiIgeT0iMTIiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJ3aGl0ZSI+CjxwYXRoIGQ9Ik0xMiAxMmMyLjIxIDAgNC0xLjc5IDQtNHMtMS43OS00LTQtNC00IDEuNzktNCA0IDEuNzkgNCA0IDR6bTAgMmMtMi42NyAwLTggMS4zNC04IDR2MmgxNnYtMmMwLTIuNjYtNS4zMy00LTgtNHoiLz4KPC9zdmc+Cjwvc3ZnPgo='">
                            <div class="contact-info">
                                <span class="name">Administrador</span>
                                <span class="status">En línea</span>
                                <span class="last-message">Revisa los nuevos pedidos</span>
                            </div>
                        </div>
                        <div class="contact">
                            <img src="../../public/imag/67f60dde26f67_lobplaza (2).png" alt="Proveedor" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiMzMDZhOTEiLz4KPHN2ZyB4PSIxMiIgeT0iMTIiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJ3aGl0ZSI+CjxwYXRoIGQ9Ik0xMiAxMmMyLjIxIDAgNC0xLjc5IDQtNHMtMS43OS00LTQtNC00IDEuNzktNCA0IDEuNzkgNCA0IDR6bTAgMmMtMi42NyAwLTggMS4zNC04IDR2MmgxNnYtMmMwLTIuNjYtNS4zMy00LTgtNHoiLz4KPC9zdmc+Cjwvc3ZnPgo='">
                            <div class="contact-info">
                                <span class="name">Proveedor ABC</span>
                                <span class="status">En línea</span>
                                <span class="last-message">Confirmado el envío</span>
                            </div>
                            <span class="badge">1</span>
                        </div>
                    </div>
                </div>

                <!-- Área de chat -->
                <div class="chat-area">
                    <div class="chat-header">
                        <div class="user-info">
                            <img src="../../public/imag/67f5eac12334e_Nueva Foto Carnet (1).jpg" alt="Cliente 1" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGRjZCMDAiLz4KPHN2ZyB4PSIxMiIgeT0iMTIiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJ3aGl0ZSI+CjxwYXRoIGQ9Ik0xMiAxMmMyLjIxIDAgNC0xLjc5IDQtNHMtMS43OS00LTQtNC00IDEuNzktNCA0IDEuNzkgNCA0IDR6bTAgMmMtMi42NyAwLTggMS4zNC04IDR2MmgxNnYtMmMwLTIuNjYtNS4zMy00LTgtNHoiLz4KPC9zdmc+Cjwvc3ZnPgo='">
                            <div>
                                <h4>Cliente 1</h4>
                                <small>En línea</small>
                            </div>
                        </div>
                        <div class="chat-actions">
                            <i class='bx bx-phone' title="Llamar"></i>
                            <i class='bx bx-video' title="Videollamada"></i>
                            <i class='bx bx-info-circle' title="Información"></i>
                        </div>
                    </div>
                    
                    <div class="chat-messages">
                        <div class="message received">
                            <img src="../../public/imag/67f5eac12334e_Nueva Foto Carnet (1).jpg" alt="Cliente 1" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGRjZCMDAiLz4KPHN2ZyB4PSIxMiIgeT0iMTIiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJ3aGl0ZSI+CjxwYXRoIGQ9Ik0xMiAxMmMyLjIxIDAgNC0xLjc5IDQtNHMtMS43OS00LTQtNC00IDEuNzktNCA0IDEuNzkgNCA0IDR6bTAgMmMtMi42NyAwLTggMS4zNC04IDR2MmgxNnYtMmMwLTIuNjYtNS4zMy00LTgtNHoiLz4KPC9zdmc+Cjwvc3ZnPgo='">
                            <div class="message-content">
                                <p>Hola, tengo una pregunta sobre mi pedido #12345</p>
                                <span>10:30 AM</span>
                            </div>
                        </div>
                        <div class="message sent">
                            <div class="message-content">
                                <p>¡Hola! Por supuesto, ¿en qué puedo ayudarte con tu pedido?</p>
                                <span>10:32 AM</span>
                            </div>
                        </div>
                        <div class="message received">
                            <img src="../../public/imag/67f5eac12334e_Nueva Foto Carnet (1).jpg" alt="Cliente 1" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGRjZCMDAiLz4KPHN2ZyB4PSIxMiIgeT0iMTIiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJ3aGl0ZSI+CjxwYXRoIGQ9Ik0xMiAxMmMyLjIxIDAgNC0xLjc5IDQtNHMtMS43OS00LTQtNC00IDEuNzktNCA0IDEuNzkgNCA0IDR6bTAgMmMtMi42NyAwLTggMS4zNC04IDR2MmgxNnYtMmMwLTIuNjYtNS4zMy00LTgtNHoiLz4KPC9zdmc+Cjwvc3ZnPgo='">
                            <div class="message-content">
                                <p>Quería saber si el producto "Lámpara LED Kawaii" está disponible en color rosa</p>
                                <span>10:33 AM</span>
                            </div>
                        </div>
                        <div class="message sent">
                            <div class="message-content">
                                <p>Déjame verificar el inventario para ti...</p>
                                <span>10:35 AM</span>
                            </div>
                        </div>
                        <div class="message sent">
                            <div class="message-content">
                                <p>¡Perfecto! Sí tenemos 15 unidades disponibles en color rosa. ¿Te gustaría que actualice tu pedido?</p>
                                <span>10:36 AM</span>
                            </div>
                        </div>
                        <div class="message received">
                            <img src="../../public/imag/67f5eac12334e_Nueva Foto Carnet (1).jpg" alt="Cliente 1" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGRjZCMDAiLz4KPHN2ZyB4PSIxMiIgeT0iMTIiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJ3aGl0ZSI+CjxwYXRoIGQ9Ik0xMiAxMmMyLjIxIDAgNC0xLjc5IDQtNHMtMS43OS00LTQtNC00IDEuNzktNCA0IDEuNzkgNCA0IDR6bTAgMmMtMi42NyAwLTggMS4zNC04IDR2MmgxNnYtMmMwLTIuNjYtNS4zMy00LTgtNHoiLz4KPC9zdmc+Cjwvc3ZnPgo='">
                            <div class="message-content">
                                <p>¡Excelente! Sí, por favor actualízalo. ¿Cuándo llegará mi pedido?</p>
                                <span>10:37 AM</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chat-input">
                        <input type="text" placeholder="Escribe un mensaje..." aria-label="Mensaje">
                        <button type="button" aria-label="Enviar mensaje">
                            <i class='bx bx-send'></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </section>
    
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <script src="../../public/js/barraprove.js.js"></script>
    
    <script>
        // Funcionalidad básica del chat
        document.addEventListener('DOMContentLoaded', function() {
            // Cambiar contacto activo
            const contacts = document.querySelectorAll('.contact');
            contacts.forEach(contact => {
                contact.addEventListener('click', function() {
                    contacts.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Enviar mensaje
            const chatInput = document.querySelector('.chat-input input');
            const sendButton = document.querySelector('.chat-input button');
            
            function sendMessage() {
                const message = chatInput.value.trim();
                if (message) {
                    const messagesContainer = document.querySelector('.chat-messages');
                    const newMessage = document.createElement('div');
                    newMessage.className = 'message sent';
                    newMessage.innerHTML = `
                        <div class="message-content">
                            <p>${message}</p>
                            <span>${new Date().toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'})}</span>
                        </div>
                    `;
                    messagesContainer.appendChild(newMessage);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    chatInput.value = '';
                }
            }
            
            sendButton.addEventListener('click', sendMessage);
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>
</body>
</html>
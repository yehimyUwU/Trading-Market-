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
    <!-- Barra lateral original -->
    <?php require '../../controllers/php/barra_prove.php'; ?>
    <section id="content">
    <div class="chat-card" style="margin-top: 30px;">
            <div class="chat-layout">
                <!-- Panel izquierdo: Lista de contactos -->
                <aside class="chat-sidebar">
                    <div class="chat-search">
                        <input type="text" placeholder="Buscar...">
                        <i class='bx bx-search'></i>
                    </div>
                    <ul class="chat-contacts">
                        <li class="contact active">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Ben Smith">
                            <div class="contact-info">
                                <span class="name">Ben Smith</span>
                                <span class="last-message">Lorem ipsum dolor sit.</span>
                            </div>
                            <span class="status online"></span>
                        </li>
                        <li class="contact">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Kate Moss">
                            <div class="contact-info">
                                <span class="name">Kate Moss</span>
                                <span class="last-message">Lorem ipsum dolor sit.</span>
                            </div>
                            <span class="status offline"></span>
                        </li>
                        <li class="contact">
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Ashley Olsen">
                            <div class="contact-info">
                                <span class="name">Ashley Olsen</span>
                                <span class="last-message">Lorem ipsum dolor sit.</span>
                            </div>
                            <span class="status busy"></span>
                        </li>
                        <li class="contact">
                            <img src="https://randomuser.me/api/portraits/men/12.jpg" alt="Danny McChain">
                            <div class="contact-info">
                                <span class="name">Danny McChain</span>
                                <span class="last-message">Lorem ipsum dolor sit.</span>
                            </div>
                            <span class="status online"></span>
                        </li>
                        <li class="contact">
                            <img src="https://randomuser.me/api/portraits/women/22.jpg" alt="Alexa Chung">
                            <div class="contact-info">
                                <span class="name">Alexa Chung</span>
                                <span class="last-message">Lorem ipsum dolor sit.</span>
                            </div>
                            <span class="status offline"></span>
                        </li>
                    </ul>
                </aside>
                <!-- Panel derecho: Área de chat -->
                <main class="chat-main">
                    <div class="chat-messages-area">
                        <div class="message-row received">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Ben Smith">
                            <div class="message-bubble">
                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                                <span class="message-meta">12:00 PM | Aug 13</span>
                            </div>
                        </div>
                        <div class="message-row sent">
                            <div class="message-bubble">
                                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                <span class="message-meta">12:00 PM | Aug 13</span>
                            </div>
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Ashley Olsen">
                        </div>
                        <div class="message-row received">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Ben Smith">
                            <div class="message-bubble">
                                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                                <span class="message-meta">12:00 PM | Aug 13</span>
                            </div>
                        </div>
                        <div class="message-row sent">
                            <div class="message-bubble">
                                <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                                <span class="message-meta">12:00 PM | Aug 13</span>
                            </div>
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Ashley Olsen">
                        </div>
                    </div>
                    <div class="chat-input-bar">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Ashley Olsen">
                        <input type="text" placeholder="Escribe un mensaje...">
                        <button><i class='bx bx-send'></i></button>
                        <button type="button"><i class='bx bx-paperclip'></i></button>
                        <button type="button"><i class='bx bx-smile'></i></button>
                    </div>
                </main>
            </div>
        </div>
    </section>
    <script src="../../public/js/barraprove.js.js"></script>
    <!-- Ionicons CDN para iconos barra lateral -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
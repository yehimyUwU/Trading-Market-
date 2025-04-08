<!DOCTYPE html>
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
<?php
    
    require '../php/barra_prove.php'; 
?>
    
    <section id="content">
        <nav>
            
            <a href="#" class="nav-link">Chat</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Buscar contacto...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <a href="#" class="notification">
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
            </div>

            <div class="chat-interface">
                <!-- Lista de contactos -->
                <div class="contacts-list">
                    <div class="head">
                        <h3>Personas disponibles</h3>
                        <i class='bx bx-search'></i>
                    </div>
                    
                    <div class="contacts">
                        <div class="contact active">
                            <img src="img/people.png" alt="Usuario">
                            <div class="contact-info">
                                <span class="name">Cliente 1</span>
                                <span class="status">En línea</span>
                                <span class="last-message">¿Tienes este producto en stock?</span>
                            </div>
                            <span class="badge">3</span>
                        </div>
                        <div class="contact">
                            <img src="img/people.png" alt="Usuario">
                            <div class="contact-info">
                                <span class="name">Cliente 2</span>
                                <span class="status">Ausente</span>
                                <span class="last-message">Gracias por la ayuda</span>
                            </div>
                        </div>
                        <div class="contact">
                            <img src="img/people.png" alt="Usuario">
                            <div class="contact-info">
                                <span class="name">Administrador</span>
                                <span class="status">En línea</span>
                                <span class="last-message">Revisa los nuevos pedidos</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Área de chat -->
                <div class="chat-area">
                    <div class="chat-header">
                        <div class="user-info">
                            <img src="img/people.png" alt="Usuario">
                            <div>
                                <h4>Cliente 1</h4>
                                <small>En línea</small>
                            </div>
                        </div>
                        <div class="chat-actions">
                            <i class='bx bx-phone'></i>
                            <i class='bx bx-info-circle'></i>
                        </div>
                    </div>
                    
                    <div class="chat-messages">
                        <div class="message received">
                            <img src="img/people.png" alt="Usuario">
                            <div class="message-content">
                                <p>Hola, tengo una pregunta sobre mi pedido</p>
                                <span>10:30 AM</span>
                            </div>
                        </div>
                        <div class="message sent">
                            <div class="message-content">
                                <p>¡Claro! ¿En qué puedo ayudarte?</p>
                                <span>10:32 AM</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chat-input">
                        <input type="text" placeholder="Escribe un mensaje...">
                        <button><i class='bx bx-send'></i></button>
                    </div>
                </div>
            </div>
        </main>
    </section>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
        <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
        
    <script src="../../public/js/barraprove.js.js"></script>
</body>
</html>
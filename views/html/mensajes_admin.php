/**
 * Archivo: mensajes_admin.php
 * Descripción: Panel de administración para gestionar solicitudes de proveedores
 * Conexiones:
 * - Se conecta con: controllers/php/barra_admin.php (barra de navegación)
 * - Se conecta con: controllers/php/obtener_solicitudes_proveedor.php (obtener solicitudes)
 * - Se conecta con: controllers/php/gestionar_solicitud_proveedor.php (gestionar solicitudes)
 * - Utiliza estilos de: public/Estilos/estilos_mensajes.css
 * - Utiliza scripts de: public/js/admin.js, public/js/mensajes_admin.js, public/js/config.js
 */

<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- Configuración básica del documento -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Enlaces a hojas de estilo -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="../../public/Estilos/bootstrap.min.css">
        <link rel="stylesheet" href="js/bootstrap.bundle.js">
        <title>Panel de Administrador</title>
        <!--Agregar unos estilos bien belicos-->
        <link rel="stylesheet" href="../../public/Estilos/estilos_mensajes.css" />
    
    </head>
<body>



<?php
// Incluye la barra de navegación del administrador
require '../../controllers/php/barra_admin.php'; 
?>



    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>

            <div class="search">
                <label>
                    <input type="text" placeholder="Busca aqui">
                    <ion-icon name="search-outline"></ion-icon>
                </label>
            </div>

            <div>
                <button id="modo-visual-btn" class="modo-visual-btn rounded-pill"><ion-icon name="moon-outline"></ion-icon></button>
            </div>

            <div class="user">

                <img src="../../public/imagenes/cucu.jpg" alt="" id="profileImage">

            </div>
        </div>
        <br>
        

        <!-- Tabla de Mensajes -->
        <div class="tabla-mensajes">
            <h2>Solicitudes de Proveedores</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Documento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaSolicitudes">
                    <!-- Las solicitudes se cargarán dinámicamente aquí -->
                </tbody>
            </table>
        </div>

        <!-- Formulario para Responder -->
        <div class="formulario-respuesta">
            <h2>Responder Mensaje</h2>
            <form id="respuesta-form">
                <label for="nombre">Para:</label>
                <input type="text" id="nombre" name="nombre" readonly>
                
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" readonly>
                
                <label for="respuesta">Mensaje:</label>
                <textarea id="respuesta" name="respuesta" rows="5" placeholder="Escribe tu respuesta aquí"></textarea>
                
                <div class="botones">
                    <button type="submit" class="btn-enviar">Enviar</button>
                    <button type="button" id="btn-limpiar" class="btn-limpiar">Limpiar</button>
                </div>
            </form>
        </div>
        
    </div>

<!--Modal para ver perfil-->  


<div id="profileModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Perfil de usuario</h2>
          <div class="user2">
              <img src="../imagenes/cucu.jpg" alt="">
          </div>
          
          <!-- Formularios de entrada -->
          <form id="profileForm">
          <label for="name">Nombre:</label>
          <input type="text" id="name" name="name" value="" disabled>

           <label for="lastname">Apellido:</label>
           <input type="text" id="lastname" name="lastname" value="" disabled>

           <label for="document">Documento:</label>
           <input type="text" id="document" name="document" value="" disabled>

           <label for="email">Email:</label>
           <input type="email" id="email" name="email" value="" disabled>

           <label for="birthdate">Fecha de nacimiento:</label>
           <input type="text" id="birthdate" name="birthdate" value="" disabled>

           <label for="gender">Género:</label>
           <input type="text" id="gender" name="gender" value="" disabled>
              <br>
              <br>
            
              <button type="button" id="editButton">Editar</button> <!-- Botón Editar -->
              <button type="submit" id="saveButton">Guardar</button> <!-- Botón Guardar -->
            </form>
          </form>
        </div>
      </div>

    

    <!--Scripts bien gotys-->
    <script src="../../public/js/admin.js"></script>
    <script src="../../public/js/mensajes_admin.js"></script>
    <script src="../../public/js/config.js"></script>
    <script>
        /**
         * Función: cargarSolicitudes()
         * Descripción: Obtiene las solicitudes de proveedores del servidor y las muestra en la tabla
         * Conexión: Realiza una petición GET a controllers/php/obtener_solicitudes_proveedor.php
         * Flujo:
         * 1. Hace una petición fetch al servidor
         * 2. Procesa la respuesta JSON
         * 3. Genera dinámicamente las filas de la tabla con los datos recibidos
         * 4. Maneja errores y casos de datos vacíos
         */
        document.addEventListener("DOMContentLoaded", function() {
            function cargarSolicitudes() {
                fetch('../../controllers/php/obtener_solicitudes_proveedor.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const tabla = document.getElementById('tablaSolicitudes');
                        tabla.innerHTML = '';
                        
                        if (!data.success) {
                            tabla.innerHTML = '<tr><td colspan="5" class="text-center">Error al cargar las solicitudes</td></tr>';
                            return;
                        }

                        if (!data.data || data.data.length === 0) {
                            tabla.innerHTML = '<tr><td colspan="5" class="text-center">No hay solicitudes de proveedores pendientes</td></tr>';
                            return;
                        }
                        
                        data.data.forEach(solicitud => {
                            const fila = document.createElement('tr');
                            fila.setAttribute('data-id', solicitud.id);
                            const botones = solicitud.estado === 'Pendiente' ? 
                                `<button class="btn-aceptar" onclick="gestionarSolicitud(${solicitud.id}, 'aceptar')">Aceptar</button>
                                 <button class="btn-negar" onclick="gestionarSolicitud(${solicitud.id}, 'negar')">Negar</button>` :
                                '';
                            
                            fila.innerHTML = `
                                <td>${solicitud.nombre}</td>
                                <td>${solicitud.email}</td>
                                <td>${solicitud.documento}</td>
                                <td>${solicitud.estado}</td>
                                <td>${botones}</td>
                            `;
                            tabla.appendChild(fila);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const tabla = document.getElementById('tablaSolicitudes');
                        tabla.innerHTML = '<tr><td colspan="5" class="text-center">Error al cargar las solicitudes</td></tr>';
                    });
            }

            // Cargar las solicitudes al iniciar la página
            cargarSolicitudes();
        });

        /**
         * Función: gestionarSolicitud(id, accion)
         * Descripción: Procesa la aceptación o rechazo de una solicitud de proveedor
         * Parámetros:
         * - id: ID de la solicitud a gestionar
         * - accion: 'aceptar' o 'negar'
         * Conexión: Realiza una petición POST a controllers/php/gestionar_solicitud_proveedor.php
         * Flujo:
         * 1. Envía los datos al servidor
         * 2. Actualiza la interfaz según la respuesta
         * 3. Elimina o actualiza la fila de la tabla según la acción
         */
        function gestionarSolicitud(id, accion) {
            fetch('../../controllers/php/gestionar_solicitud_proveedor.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&accion=${accion}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Actualizar la tabla sin recargar
                    const tabla = document.getElementById('tablaSolicitudes');
                    const fila = tabla.querySelector(`tr[data-id="${id}"]`);
                    
                    if (fila) {
                        if (accion === 'aceptar') {
                            fila.remove(); // Eliminar la fila si se acepta
                        } else if (accion === 'negar') {
                            // Actualizar el estado a Cancelada y ocultar los botones
                            const estadoCell = fila.querySelector('td:nth-child(4)');
                            const accionesCell = fila.querySelector('td:nth-child(5)');
                            estadoCell.textContent = 'Cancelada';
                            accionesCell.innerHTML = '';
                        }
                    }

                    // Verificar si quedan solicitudes
                    if (tabla.children.length === 0) {
                        tabla.innerHTML = '<tr><td colspan="5" class="text-center">No hay solicitudes de proveedores pendientes</td></tr>';
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

<?php

/**
 * Archivo: gestionar_solicitud_proveedor.php
 * Descripción: Controlador para gestionar las solicitudes de proveedores
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: views/html/mensajes_admin.php
 * - Interactúa con las tablas: usuario, rol_usuario, usuario_rol
 * Flujo general:
 * 1. Recibe una solicitud POST con el ID del usuario y la acción a realizar
 * 2. Procesa la solicitud según la acción (aceptar o negar)
 * 3. Actualiza los roles y estados en la base de datos
 * 4. Retorna una respuesta JSON con el resultado
 */

require '../../config/php/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $accion = $_POST['accion'] ?? null;

    if (!$id || !$accion) {
        echo json_encode(['success' => false, 'message' => 'Faltan parámetros requeridos']);
        exit;
    }

    try {
        $pdo = Conexion::conectar();
        
        if ($accion === 'aceptar') {
            /**
             * Proceso de aceptación de solicitud:
             * 1. Obtiene el ID del rol de proveedor
             * 2. Verifica si el usuario ya tiene el rol
             * 3. Asigna el rol de proveedor si no lo tiene
             * 4. Actualiza el estado de la solicitud
             * Consultas SQL:
             * - SELECT id_rol FROM rol_usuario WHERE nombre = 'Proveedor'
             * - SELECT COUNT(*) FROM usuario_rol WHERE id_usuario = ? AND id_rol = ?
             * - INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)
             * - UPDATE usuario SET solicitud_proveedor = 0 WHERE id_usuario = ?
             */
            $stmt_rol = $pdo->prepare("SELECT id_rol FROM rol_usuario WHERE nombre = 'Proveedor'");
            $stmt_rol->execute();
            $id_rol = $stmt_rol->fetchColumn();

            if (!$id_rol) {
                echo json_encode(['success' => false, 'message' => 'Error: Rol de proveedor no encontrado']);
                exit;
            }

            // Verificar si el usuario ya tiene el rol de proveedor
            $stmt_verificar = $pdo->prepare("
                SELECT COUNT(*) FROM usuario_rol 
                WHERE id_usuario = ? AND id_rol = ?
            ");
            $stmt_verificar->execute([$id, $id_rol]);
            
            if ($stmt_verificar->fetchColumn() == 0) {
                // Asignar el rol de proveedor al usuario
                $stmt = $pdo->prepare("
                    INSERT INTO usuario_rol (id_usuario, id_rol) 
                    VALUES (?, ?)
                ");
                
                if ($stmt->execute([$id, $id_rol])) {
                    // Actualizar el estado de la solicitud
                    $stmt = $pdo->prepare("
                        UPDATE usuario 
                        SET solicitud_proveedor = 0 
                        WHERE id_usuario = ?
                    ");
                    $stmt->execute([$id]);
                    echo json_encode(['success' => true, 'message' => 'Solicitud aceptada correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al aceptar la solicitud']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'El usuario ya tiene el rol de proveedor']);
            }
        } elseif ($accion === 'negar') {
            /**
             * Proceso de negación de solicitud:
             * 1. Actualiza el estado de la solicitud a cancelada
             * Consulta SQL:
             * - UPDATE usuario SET solicitud_proveedor = 2 WHERE id_usuario = ?
             */
            $stmt = $pdo->prepare("
                UPDATE usuario 
                SET solicitud_proveedor = 2 
                WHERE id_usuario = ?
            ");
            
            if ($stmt->execute([$id])) {
                echo json_encode(['success' => true, 'message' => 'Solicitud cancelada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al cancelar la solicitud']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?> 
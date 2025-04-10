<?php
/**
 * Archivo: obtener_solicitudes_proveedor.php
 * Descripción: Controlador para obtener las solicitudes de proveedores pendientes
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: views/html/mensajes_admin.php
 * - Interactúa con las tablas: usuario, rol_usuario, usuario_rol
 * Flujo general:
 * 1. Establece la conexión con la base de datos
 * 2. Realiza una consulta JOIN compleja para obtener las solicitudes
 * 3. Procesa los resultados y determina el estado de cada solicitud
 * 4. Retorna una respuesta JSON con los datos
 */

require '../../config/php/conexion.php';
header('Content-Type: application/json');

try {
    $pdo = Conexion::conectar();
    
    /**
     * Consulta SQL para obtener solicitudes de proveedores:
     * - Selecciona información básica del usuario (id, nombre, email, documento)
     * - Determina el estado de la solicitud basado en:
     *   * solicitud_proveedor = 2: Cancelada
     *   * No tiene rol de proveedor: Pendiente
     *   * Tiene rol de proveedor: Aprobado
     * - Filtra solo usuarios con solicitud_proveedor = 1 o 2
     * - Ordena por fecha de nacimiento descendente
     * 
     * Tablas involucradas:
     * - usuario: Información básica del usuario
     * - usuario_rol: Roles asignados a los usuarios
     * - rol_usuario: Definición de roles del sistema
     */
    $stmt = $pdo->prepare("
        SELECT 
            u.id_usuario as id,
            u.nombre,
            u.email,
            u.documento,
            CASE 
                WHEN u.solicitud_proveedor = 2 THEN 'Cancelada'
                WHEN ur.id_rol IS NULL THEN 'Pendiente'
                ELSE 'Aprobado'
            END as estado
        FROM 
            usuario u
        LEFT JOIN 
            usuario_rol ur ON u.id_usuario = ur.id_usuario
        LEFT JOIN 
            rol_usuario r ON ur.id_rol = r.id_rol AND r.nombre = 'Proveedor'
        WHERE 
            u.solicitud_proveedor IN (1, 2)
        ORDER BY 
            u.fecha_nacimiento DESC
    ");
    
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Asegurarnos de que siempre devolvemos un array
    if (!is_array($solicitudes)) {
        $solicitudes = [];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $solicitudes,
        'count' => count($solicitudes)
    ]);
} catch (PDOException $e) {
    error_log("Error en obtener_solicitudes_proveedor.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener las solicitudes: ' . $e->getMessage(),
        'data' => []
    ]);
}
?> 
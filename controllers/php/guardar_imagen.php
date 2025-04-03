<?php
session_start();
require '../../config/php/conexion.php'; // Asegúrate de tener la conexión a la base de datos configurada
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../public/imag/';
        $fileName = uniqid() . '_' . basename($_FILES['profileImage']['name']);
        $uploadFile = $uploadDir . $fileName;

        // Validar el tipo de archivo
        $fileType = mime_content_type($_FILES['profileImage']['tmp_name']);
        if (!in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
            echo json_encode(['success' => false, 'message' => 'Formato de imagen no válido.']);
            exit;
        }

        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadFile)) {
            try {
                $conn = Conexion::conectar();
                $idUsuario = $_SESSION['usuario']['id']; // Asegúrate de que el ID del usuario esté en la sesión

                // Actualizar la ruta de la imagen en la base de datos
                $stmt = $conn->prepare("UPDATE usuario SET imagen = :imagen WHERE id_usuario = :id_usuario");
                $stmt->bindParam(':imagen', $fileName, PDO::PARAM_STR);
                $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
                $stmt->execute();

                echo json_encode(['success' => true, 'fileName' => $fileName]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la imagen en la base de datos: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al mover el archivo.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se recibió ninguna imagen o hubo un error al subirla.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}

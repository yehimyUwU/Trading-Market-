<?php

class Conexion {

    public static function conectar() {
        $nombreServidor = "localhost";
        $usuario = "root";
        $password = "";
        $baseDatos = "inventarios";
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$nombreServidor;dbname=$baseDatos;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $objConexion = new PDO($dsn, $usuario, $password, $options);
            $objConexion->exec("set names utf8");
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }

        return $objConexion;
    }
}
?>



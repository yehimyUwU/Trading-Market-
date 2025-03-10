<?php

class Conexion{

    public static function conectar(){
        $nombreServidor = "localhost";
        $usuario = "root";
        $password = "Recuerdame545";
        $baseDatos = "inventarios1115";

        try {
            $objConexion = new PDO('mysql:host='.$nombreServidor.';dbname='.$baseDatos.';',$usuario,$password);
            $objConexion -> exec("set names utf8");
        
        } catch (Exception $e) {
            $objConexion = $e -> getMessage();
        }
        return $objConexion;
    }
}

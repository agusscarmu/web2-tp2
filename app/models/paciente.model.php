<?php

class PacienteModel {

    public $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=consultorio;charset=utf8', 'root', '');
    }

    public function getAll() {
        // 1. abro conexión a la DB
        // ya esta abierta por el constructor de la clase

        // 2. ejecuto la sentencia (2 subpasos)
        $query = $this->db->prepare("SELECT pacientes.id, pacientes.nombre, pacientes.edad, pacientes.dni, pacientes.motivo, obrasocial.nombre as obrasocial 

        FROM pacientes 

        INNER JOIN obrasocial ON (pacientes.ID_obrasocial=obrasocial.id) 
        
        ORDER BY id DESC LIMIT 10");
        $query->execute();

        // 3. obtengo los resultados
        $paciente = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de objetos
        
        return $paciente;
    }

    public function get($id) {
        $query = $this->db->prepare("SELECT * FROM pacientes WHERE id = ?");
        $query->execute([$id]);
        $paciente = $query->fetch(PDO::FETCH_OBJ);
        
        return $paciente;
    }

    function delete($id) {
        $query = $this->db->prepare('DELETE FROM pacientes WHERE id = ?');
        $query->execute([$id]);
    }

    public function insert($nombre, $dni, $edad, $motivo, $obrasocial){
        $query = $this->db->prepare("INSERT INTO pacientes (nombre, edad, dni, motivo, ID_obrasocial) VALUES (?, ?, ?, ?, ?)");
        $query->execute([$nombre, $edad, $dni, $motivo, $obrasocial]);

        return $this->db->lastInsertId();
    }

    public function update ($id, $nombre, $dni, $edad, $motivo, $obrasocial){
        $query= $this->db->prepare("UPDATE pacientes SET nombre=?,edad=?,dni=?,motivo=?,ID_obrasocial=? WHERE id=? ");
        $query-> execute([$nombre, $edad, $dni, $motivo, $obrasocial,$id]);  
    }
}
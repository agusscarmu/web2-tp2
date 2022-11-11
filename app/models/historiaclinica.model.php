<?php

class HcModel {

    private $db;
    private $columns;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=consultorio;charset=utf8', 'root', '');
        $this->columns = array('ID', 'paciente', 'servicio_de_atencion', 'historia_clinica', 'fecha');
    }

    public function getColumns(){
        return $this->columns;
    }

    public function getAll() {
        // 1. abro conexión a la DB
        // ya esta abierta por el constructor de la clase

        // 2. ejecuto la sentencia (2 subpasos)
        $query = $this->db->prepare('SELECT historiaclinica.id, pacientes.nombre as paciente, historiaclinica.servicio_de_atencion, historiaclinica.historia_clinica, historiaclinica.fecha
                                    FROM historiaclinica 
                                    INNER JOIN pacientes ON (historiaclinica.ID_pacientes=pacientes.id)');
        $query->execute();

        // 3. obtengo los resultados
        $paciente = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de objetos
        
        return $paciente;
    }

    public function getAllOrderBy($sort, $order= 'ASC'){
        $query = $this->db->prepare('SELECT historiaclinica.id, pacientes.nombre as paciente, historiaclinica.servicio_de_atencion, historiaclinica.historia_clinica, historiaclinica.fecha
                                    FROM historiaclinica 
                                    INNER JOIN pacientes ON (historiaclinica.ID_pacientes=pacientes.id) 
                                    ORDER BY '.$sort.' '.$order);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function get($id) {
        $query = $this->db->prepare("SELECT historiaclinica.id, pacientes.nombre as paciente, historiaclinica.servicio_de_atencion, historiaclinica.historia_clinica, historiaclinica.fecha
                                    FROM historiaclinica 
                                    INNER JOIN pacientes ON (historiaclinica.ID_pacientes=pacientes.id) WHERE historiaclinica.id = ?");
        $query->execute([$id]);
        $paciente = $query->fetch(PDO::FETCH_OBJ);
        
        return $paciente;
    }

    public function filterOrderByPaciente($paciente, $sort, $order='ASC'){
        $query = $this->db->prepare('SELECT historiaclinica.id, pacientes.nombre as paciente, historiaclinica.servicio_de_atencion, historiaclinica.historia_clinica, historiaclinica.fecha
                                    FROM historiaclinica 
                                    INNER JOIN pacientes ON (historiaclinica.ID_pacientes=pacientes.id) WHERE pacientes.nombre = ?
                                    ORDER BY '.$sort.' '.$order);
        $query->execute([$paciente]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function filterByPaciente($paciente){
        $query = $this->db->prepare("SELECT historiaclinica.id, pacientes.nombre as paciente, historiaclinica.servicio_de_atencion, historiaclinica.historia_clinica, historiaclinica.fecha
                                    FROM historiaclinica 
                                    INNER JOIN pacientes ON (historiaclinica.ID_pacientes=pacientes.id) WHERE pacientes.nombre = ?");
        $query->execute([$paciente]);
        $paciente = $query->fetchAll(PDO::FETCH_OBJ);
        return $paciente;
    }

    function delete($id) {
        $query = $this->db->prepare('DELETE FROM historiaclinica WHERE id = ?');
        $query->execute([$id]);
    }

    public function insert($paciente, $servicio, $historiaclinica, $fecha){
        $query = $this->db->prepare("INSERT INTO historiaclinica (ID_pacientes, servicio_de_atencion, historia_clinica, fecha) VALUES (?, ?, ?, ?)");
        $query->execute([$paciente, $servicio, $historiaclinica, $fecha]);

        return $this->db->lastInsertId();
    }

    public function update ($id, $servicio, $historiaclinica, $fecha){
        $query = $this->db->prepare("UPDATE historiaclinica SET servicio_de_atencion=?,historia_clinica=?,fecha=? WHERE id=?");
        $query -> execute([$servicio, $historiaclinica, $fecha, $id]);}

}
    

<?php

class PacienteModel {

    private $db;
    private $columns;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=consultorio;charset=utf8', 'root', '');
        $this->columns = array('ID', 'nombre', 'edad', 'dni', 'motivo', 'obrasocial');
    }

    public function getColumns(){
        return $this->columns;
    }

    public function getAll() {
        // 1. abro conexión a la DB
        // ya esta abierta por el constructor de la clase

        // 2. ejecuto la sentencia (2 subpasos)
        $query = $this->db->prepare("SELECT pacientes.id, pacientes.nombre, pacientes.edad, pacientes.dni, pacientes.motivo, pacientes.imagen, obrasocial.nombre as obrasocial
                                    FROM pacientes 
                                    INNER JOIN obrasocial ON (pacientes.ID_obrasocial=obrasocial.id)");
        $query->execute();

        // 3. obtengo los resultados
        $paciente = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de objetos
        
        return $paciente;
    }

    public function getAllOrderBy($sort, $order= 'ASC'){
        $query = $this->db->prepare('SELECT pacientes.id, pacientes.nombre, pacientes.edad, pacientes.dni, pacientes.motivo, obrasocial.nombre as obrasocial 
                                    FROM pacientes 
                                    INNER JOIN obrasocial ON (pacientes.ID_obrasocial=obrasocial.id) 
                                    ORDER BY '.$sort.' '.$order);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function filterByOs($obrasocial){
        $query = $this->db->prepare("SELECT pacientes.id, pacientes.nombre, pacientes.edad, pacientes.dni, pacientes.motivo, obrasocial.nombre as nombre2
                                    FROM pacientes                       
                                    INNER JOIN obrasocial ON (pacientes.ID_obrasocial=obrasocial.id)  WHERE obrasocial.nombre = ?");
        $query -> execute([$obrasocial]);
        $px = $query->fetchAll(PDO::FETCH_OBJ);
        return $px;
    }

    public function filterOrderByOs($obrasocial, $sort, $order){
        $query = $this->db->prepare("SELECT pacientes.id, pacientes.nombre, pacientes.edad, pacientes.dni, pacientes.motivo, obrasocial.nombre as nombre2
                                    FROM pacientes                       
                                    INNER JOIN obrasocial ON (pacientes.ID_obrasocial=obrasocial.id)  WHERE obrasocial.nombre = ?
                                    ORDER BY '.$sort.' '.$order");
        $query -> execute([$obrasocial]);
        $px = $query->fetchAll(PDO::FETCH_OBJ);
        return $px;
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

    public function insert($nombre, $edad, $dni, $motivo, $obrasocial){
        $query = $this->db->prepare("INSERT INTO pacientes (nombre, edad, dni, motivo, ID_obrasocial) VALUES (?, ?, ?, ?, ?)");
        $query->execute([$nombre, $edad, $dni, $motivo, $obrasocial]);

        return $this->db->lastInsertId();
    }

    public function update ($id, $nombre, $edad, $dni, $motivo, $obrasocial,$imagen=null){
        $pathImg = null;

        if ($imagen){
            $pathImg = $this->subirImagen($imagen);
        $query = $this->db->prepare("UPDATE pacientes SET nombre=?,edad=?,dni=?,motivo=?,imagen=?,ID_obrasocial=? WHERE id=?");
        $query -> execute([$nombre, $edad, $dni, $motivo, $pathImg, $obrasocial, $id]);}
        else{  
        $query = $this->db->prepare("UPDATE pacientes SET nombre=?,edad=?,dni=?,motivo=?,ID_obrasocial=? WHERE id=?");
        $query -> execute([$nombre, $edad, $dni, $motivo, $obrasocial, $id]);}
    }
    
    private function subirImagen($imagen){
        $temporal = './imgs/' . uniqid() . '.jpg';
        move_uploaded_file($imagen, $temporal);
        return $temporal;

    }
}
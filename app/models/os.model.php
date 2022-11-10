<?php

class OsModel {

    private $db;
    private $columns;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=consultorio;charset=utf8', 'root', '');
        $this->columns = array('ID', 'nombre', 'tipo', 'domicilio', 'telefono');
    }

    public function getColumns(){
        return $this->columns;
    }

    public function getAll() {
        // 1. abro conexión a la DB
        // ya esta abierta por el constructor de la clase

        // 2. ejecuto la sentencia (2 subpasos)
        $query = $this->db->prepare("SELECT*FROM obrasocial");
        $query->execute();

        // 3. obtengo los resultados
        $paciente = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de objetos
        
        return $paciente;
    }

    public function getAllOrderBy($sort, $order= 'ASC'){
        $query = $this->db->prepare('SELECT*FROM obrasocial ORDER BY '.$sort.' '.$order);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function get($id) {
        $query = $this->db->prepare("SELECT * FROM obrasocial WHERE id = ?");
        $query->execute([$id]);
        $paciente = $query->fetch(PDO::FETCH_OBJ);
        
        return $paciente;
    }

    function delete($id) {
        $query = $this->db->prepare('DELETE FROM obrasocial WHERE id = ?');
        $query->execute([$id]);
    }

    public function insert($nombre, $tipo, $domicilio, $telefono){
        $query = $this->db->prepare("INSERT INTO obrasocial (nombre, tipo, domicilio, telefono) VALUES (?, ?, ?, ?)");
        $query->execute([$nombre, $tipo, $domicilio, $telefono]);

        return $this->db->lastInsertId();
    }

    public function update ($id, $nombre, $tipo, $domicilio, $telefono){
        $query = $this->db->prepare("UPDATE obrasocial SET nombre=?,tipo=?,domicilio=?,telefono=? WHERE id=?");
        $query -> execute([$nombre, $tipo, $domicilio, $telefono, $id]);}

}
    

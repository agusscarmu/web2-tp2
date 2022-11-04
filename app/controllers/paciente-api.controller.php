<?php
require_once './app/models/paciente.model.php';
require_once './app/views/view.api.php';

class PacienteApiController {
    private $model;
    private $view;

    private $data;

    public function __construct() {
        $this->model = new PacienteModel();
        $this->view = new ApiView();
        
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function getPacientes($params = null) {
        $px = $this->model->getAll();
        $this->view->response($px);
    }

    public function getPaciente($params = null) {
        // obtengo el id del arreglo de params
        $id = $params[':ID'];
        $px = $this->model->get($id);

        // si no existe devuelvo 404
        if ($px)
            $this->view->response($px);
        else 
            $this->view->response("El paciente con el id=$id no existe", 404);
    }

    public function deletePaciente($params = null) {
        $id = $params[':ID'];

        $px = $this->model->get($id);
        if ($px) {
            $this->model->delete($id);
            $this->view->response($px);
        } else 
            $this->view->response("El paciente con el id=$id no existe", 404);
    }

    public function insertPaciente($params = null) {
        $paciente = $this->getData();

        if (empty($paciente->nombre) || empty($paciente->edad) || empty($paciente->dni) || empty($paciente->motivo) || empty($paciente->obrasocial) ) {
            $this->view->response("Complete los datos", 400);
        } else {
            $id = $this->model->insert($paciente->nombre, $paciente->edad, $paciente->dni, $paciente->motivo, $paciente->obrasocial);
            $paciente = $this->model->get($id);
            $this->view->response($paciente, 201);
        }
    }

    public function updatePaciente($params=null){
        $id = $params[':ID'];
        $paciente = $this->getData();
        
        if (empty($paciente->nombre) || empty($paciente->edad) || empty($paciente->dni) || empty($paciente->motivo) || empty($paciente->obrasocial) ) {
            $this->view->response("Complete los datos", 400);
        } else {
            $px = $this->model->get($id);
            if ($px) {
                $this->model->update($id, $paciente->nombre, $paciente->edad, $paciente->dni, $paciente->motivo, $paciente->obrasocial);
                $this->view->response($px);
            } else {
                $this->view->response("El paciente con el id=$id no existe", 404); 
            }
        }
    }
}
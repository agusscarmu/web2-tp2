<?php
require_once './app/models/paciente.model.php';
require_once './app/views/view.api.php';
require_once './app/helpers/auth-api.helper.php';

class PacienteApiController {
    private $model;
    private $view;
    private $helper;

    private $data;

    public function __construct() {
        $this->model = new PacienteModel();
        $this->view = new ApiView();
        $this->helper = new AuthApiHelper();
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function getPacientes($params = null) {
        $sort = $_GET['sort'];
        $order = $_GET['order'];
        $page = $_GET['page'];
        $limit = $_GET['limit'];
        $obrasocial = $_GET['obrasocial'];

        if(isset($obrasocial)){
            if(!$sort==null){
                // compruebo que el get obtenido sea correcto
                if ((in_array($sort,$this->model->getColumns())&&(($order==null)||($order=='ASC')||($order=='DESC')))) {
                    $pacientes = $this->model->filterOrderByOs($obrasocial, $sort, $order);
                    if(empty($pacientes)){
                    $this->view->response("Obra social no existe", 400);
                    }else{
                    $this->parametros($pacientes, $page, $limit);
                    }
                }else{
                    $this->view->response("Parametros GET incorrectos", 400);
                }
            }else{
                $pacientes = $this->model->filterByOs($obrasocial);
                if(empty($pacientes)){
                    $this->view->response("Obra social no existe", 400);
                }else{
                    $this->parametros($pacientes, $page, $limit);
                }
            }
        }
        else{
            if (!$sort==null){
                if ((in_array($sort,$this->model->getColumns())&&(($order==null)||($order=='ASC')||($order=='DESC')))) { // compruebo que el get obtenido sea correcto
                    $pacientes = $this->model->getAllOrderBy($sort, $order);
                    $this->parametros($pacientes,$page,$limit);
                }else{
                    $this->view->response("Parametros GET incorrectos", 400); //
                }
            }else{
                $px = $this->model->getAll();
                $this->parametros($px,$page,$limit);
            }
        }
    }

    private function parametros($pacientes,$page,$limit){
        if(isset($page)&&isset($limit)){
            // valido que el page y limit sean integer
            if ((filter_var($page, FILTER_VALIDATE_INT)!== false)&&(filter_var($limit, FILTER_VALIDATE_INT) !== false)){
              $px = array_slice($pacientes, $page*$limit, $limit);
              $this->view->response($px);
            }else{
                $this->view->response("Pagina o limite no especificados", 400); //
            }
        }else{
            $this->view->response($pacientes, 200);
        }
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
        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }
        
        $id = $params[':ID'];

        $px = $this->model->get($id);
        if ($px) {
            $this->model->delete($id);
            $this->view->response($px);
        } else 
            $this->view->response("El paciente con el id=$id no existe", 404);
    }

    public function insertPaciente($params = null) {
        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }

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
        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }

        $id = $params[':ID'];
        $paciente = $this->getData();
        
        if (empty($paciente->nombre) || empty($paciente->edad) || empty($paciente->dni) || empty($paciente->motivo) || empty($paciente->obrasocial) ) {
            $this->view->response("Complete los datos", 400);
        } else {
            $px = $this->model->get($id);
            if ($px) {
                if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png"){
                    $imagen=$_FILES['imagen']['tmp_name'];
                    $this->model->update($id, $paciente->nombre, $paciente->edad, $paciente->dni, $paciente->motivo, $paciente->obrasocial, $imagen);
                }
                   
                else{
                    $this->model->update($id, $paciente->nombre, $paciente->edad, $paciente->dni, $paciente->motivo, $paciente->obrasocial);
                }
                $this->view->response($px);
            } else {
                $this->view->response("El paciente con el id=$id no existe", 404); 
            }
        }
    }
}
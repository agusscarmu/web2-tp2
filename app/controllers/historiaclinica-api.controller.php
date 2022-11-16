<?php
require_once './app/models/historiaclinica.model.php';
require_once './app/views/view.api.php';
require_once './app/helpers/auth-api.helper.php';

class HcApiController {
    private $model;
    private $view;
    private $helper;

    private $data;

    public function __construct() {
        $this->model = new HcModel();
        $this->view = new ApiView();
        $this->helper = new AuthApiHelper();
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function getHistoriasClinicas($params = null) {
        $sort = $_GET['sort'];
        $order = $_GET['order'];
        $page = $_GET['page'];
        $limit = $_GET['limit'];
        $paciente = $_GET['paciente'];

        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }
        
        if(isset($paciente)){
            if(!$sort==null){
                // compruebo que el get obtenido sea correcto
                if ((in_array($sort,$this->model->getColumns())&&(($order==null)||($order=='ASC')||($order=='DESC')))) {
                    $element = $this->model->filterOrderByPaciente($paciente, $sort, $order);
                    if(empty($element)){
                    $this->view->response("Paciente no existe", 400);
                    }else{
                    $this->parametros($element, $page, $limit);
                    }
                }else{
                    $this->view->response("Parametros GET incorrectos", 400);
                }
            }else{
                $element = $this->model->filterByPaciente($paciente);
                if(empty($element)){
                    $this->view->response("Paciente no existe", 400);
                }else{
                    $this->parametros($element, $page, $limit);
                }
            }
        }else{
            if(!$sort==null){
                if ((in_array($sort,$this->model->getColumns())&&(($order==null)||($order=='ASC')||($order=='DESC')))) { // compruebo que el get obtenido sea correcto
                    $element = $this->model->getAllOrderBy($sort, $order);
                    $this->parametros($element,$page,$limit);
                }else{
                    $this->view->response("Parametros GET incorrectos", 400); //
                }
            }else{
                $element = $this->model->getAll();
                $this->parametros($element,$page,$limit);
            }
        }
    }

    private function parametros($element,$page,$limit){
        if(isset($page)&&isset($limit)){
            // valido que el page y limit sean integer
            if ((filter_var($page, FILTER_VALIDATE_INT)!== false)&&(filter_var($limit, FILTER_VALIDATE_INT) !== false)){
             // Con array_slice se establecen los limites para paginacion
              $list = array_slice($element, $page*$limit, $limit);
              $this->view->response($list);
            }else{
                $this->view->response("Pagina o limite no especificados", 400); //
            }
        }else{
            $this->view->response($element, 200);
        }
    }

    public function getHc($params = null) {
        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }
        // obtengo el id del arreglo de params
        $id = $params[':ID'];
        $hc = $this->model->get($id);

        // si no existe devuelvo 404
        if ($hc)
            $this->view->response($hc);
        else 
            $this->view->response("No se encontro ninguna historia clinica", 404);
    }

    public function deleteHc($params = null) {
        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }
        
        $id = $params[':ID'];

        $hc = $this->model->get($id);
        if ($hc) {
            $this->model->delete($id);
            $this->view->response($hc);
        } else 
            $this->view->response("No se encontro ninguna historia clinica", 404);
    }

    public function insertHc($params = null) {
        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }

        $hc = $this->getData();

        if (empty($hc->paciente) || empty($hc->servicio_de_atencion) || empty($hc->historia_clinica) || empty($hc->fecha)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $id = $this->model->insert($hc->paciente, $hc->servicio_de_atencion, $hc->historia_clinica, $hc->fecha);
            $hc = $this->model->get($id);
            $this->view->response($hc, 201);
        }
    }

    public function updateHc($params=null){
        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }

        $id = $params[':ID'];
        $hc = $this->getData();
        
        if (empty($hc->servicio_de_atencion) || empty($hc->historia_clinica) || empty($hc->fecha)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $historiaclinica = $this->model->get($id);
            if ($historiaclinica) {
                $this->model->update($id, $hc->servicio_de_atencion, $hc->historia_clinica, $hc->fecha);
                $this->view->response($hc);
            }else {
                $this->view->response("No se encontro ninguna historia clinica", 404); 
            }
        }
    }
}
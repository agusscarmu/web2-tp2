<?php
require_once './app/models/os.model.php';
require_once './app/views/view.api.php';
require_once './app/helpers/auth-api.helper.php';

class OsApiController {
    private $model;
    private $view;
    private $helper;

    private $data;

    public function __construct() {
        $this->model = new OsModel();
        $this->view = new ApiView();
        $this->helper = new AuthApiHelper();
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function getOs($params = null) {
        $sort = $_GET['sort'];
        $order = $_GET['order'];
        $page = $_GET['page'];
        $limit = $_GET['limit'];
    
        if(!$sort==null){
            if ((in_array($sort,$this->model->getColumns())&&(($order==null)||($order=='ASC')||($order=='DESC')))) { // compruebo que el get obtenido sea correcto
                $obra = $this->model->getAllOrderBy($sort, $order);
                $this->parametros($obra,$page,$limit);
            }else{
                $this->view->response("Parametros GET incorrectos", 400); //
            }
        }else{
            $obra = $this->model->getAll();
            $this->parametros($obra,$page,$limit);
        }
    }

    private function parametros($obra,$page,$limit){
        if(isset($page)&&isset($limit)){
            // valido que el page y limit sean integer
            if ((filter_var($page, FILTER_VALIDATE_INT)!== false)&&(filter_var($limit, FILTER_VALIDATE_INT) !== false)){
              $list = array_slice($obra, $page*$limit, $limit);
              $this->view->response($list);
            }else{
                $this->view->response("Pagina o limite no especificados", 400); //
            }
        }else{
            $this->view->response($obra, 200);
        }
    }

    public function getObra($params = null) {
        // obtengo el id del arreglo de params
        $id = $params[':ID'];
        $os = $this->model->get($id);

        // si no existe devuelvo 404
        if ($os)
            $this->view->response($os);
        else 
            $this->view->response("La obra social con el id=$id no existe", 404);
    }

    public function deleteObra($params = null) {
        $id = $params[':ID'];

        $os = $this->model->get($id);
        if ($os) {
            $this->model->delete($id);
            $this->view->response($os);
        } else 
            $this->view->response("La obra social con el id=$id no existe", 404);
    }

    public function insertObra($params = null) {
        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }

        $obrasocial = $this->getData();

        if (empty($obrasocial->nombre) || empty($obrasocial->tipo) || empty($obrasocial->domicilio) || empty($obrasocial->telefono)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $id = $this->model->insert($obrasocial->nombre, $obrasocial->tipo, $obrasocial->domicilio, $obrasocial->telefono);
            $obrasocial = $this->model->get($id);
            $this->view->response($obrasocial, 201);
        }
    }

    public function updateObra($params=null){
        if(!$this->helper->isLoggedIn()){
            $this->view->response("No estas logeado", 401);
            return;
        }

        $id = $params[':ID'];
        $obrasocial = $this->getData();
        
        if (empty($obrasocial->nombre) || empty($obrasocial->tipo) || empty($obrasocial->domicilio) || empty($obrasocial->telefono)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $os = $this->model->get($id);
            if ($os) {
                $this->model->update($id, $obrasocial->nombre, $obrasocial->tipo, $obrasocial->domicilio, $obrasocial->telefono);
                $this->view->response($os);
            }else {
                $this->view->response("La obrasocial con el id=$id no existe", 404); 
            }
        }
    }
}
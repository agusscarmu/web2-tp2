<?php
require_once './app/models/admin.model.php';
require_once './app/views/view.api.php';

class AdminApiController {
    private $model;
    private $view;

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

    public function getToken($params=null){

    }
}
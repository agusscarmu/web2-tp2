<?php
require_once './app/views/view.api.php';

class defaultRoute{

    private $view;

    public function __construct(){
        $this->view = new ApiView;
    }

    public function default(){
        $this->view->response('Pagina no encontrada', 404);
        return;
    }






}
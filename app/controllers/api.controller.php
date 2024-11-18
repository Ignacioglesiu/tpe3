<?php
require_once 'app/views/json.view.php';
require_once 'app/models/shirt.models.php';

class ApiController{
    private $model;
    private $view;

    public function __construct(){
        $this->model = new shirtModel();
        $this->view = new JSONView();
    }

    public function getAllShirts($req, $res){
        $orderBy = false;
        if(isset($req->query->orderBy)){
            $orderBy = $req->query->orderBy;
        }
        $shirts = $this->model->getShirts($orderBy);
        return $this->view->response($shirts, 200);
    }

    public function getShirt($req, $res){
        $id = $req->params->id;
        $shirt = $this->model->getShirtById($id);
        if(!$shirt){
            return $this->view->response("La camiseta con el id=$id no existe", 404);
        }
        return $this->view->response($shirt, 200);
    }

    public function updateShirt($req, $res){
        $id = $req->params->id;
        $shirt = $this->model->getShirtById($id);
        if(!$shirt){
            return $this->view->response("La camiseta con el id=$id no existe", 404);
        }
        if(empty($req->body->id_equipo) || empty($req->body->temporada) || empty($req->body->tipo) || empty($req->body->precio)){
            return $this->view->response("Faltan completar datos", 400);
        }

        $id_equipo = $req->body->id_equipo;
        $temporada = $req->body->temporada;
        $tipo = $req->body->tipo;
        $precio = $req->body->precio;

        $this->model->updateShirt($id, $id_equipo, $temporada, $tipo, $precio);
        $shirt = $this->model->getShirtById($id);
        $this->view->response($shirt, 200);
    }
}
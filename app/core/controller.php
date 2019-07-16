<?php

  /**
   * Controller
   */
class Controller {

    protected $view;

    protected $model;
    protected $params = [];

    public function __construct($params) {
        $this->params = $params;
    }

    public function create() {
        $model = new $this->model;
        $model->create($this->params);
    }

    public function updated() {
        $model = new $this->model;
        $model->updated($this->params);
    }

    public function deleted() {
        $model = new $this->model;
        $model->deleted($this->params);
    }

    public function publish() {
        $model = new $this->model;
        $model->publish($this->params);
    }

    public function view($name , $data=[]) {
        $this->view = new View($name,$data);
        // var_dump($this->view);
        return $this->view;
    }

    public function redirect($url='') {
      header("Location: " . $url ,true,303);
      exit;
    }


}

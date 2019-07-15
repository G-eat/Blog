<?php

  /**
   * Controller
   */
class Controller {

    protected $view;

    // protected $model;
    // protected $params;

    // public function __construct($model,$params) {
    //     $this->model = $model;
    //     $this->params = $params;
    // }

    // public function create($model,$params) {
    //     $model::$params();
    //     // echo $model;
    // }

    public function view($name , $data=[]) {
        $this->view = new View($name,$data);
        // var_dump($this->view);
        return $this->view;
    }

    public function redirect($url='') {
      header("Location: " . $url ,true,303);
      exit;
    }

    // public function createpost() {
    //     echo $this->model;
    // }

}

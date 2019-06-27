<?php

  /**
   * Controller
   */
class Controller {

    protected $view;

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

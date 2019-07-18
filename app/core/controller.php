<?php

namespace App\Core;

use App\Core\View;
use App\Controllers\AdminController;
use App\Controllers\CategoryController;
use App\Controllers\CommentController;
use App\Controllers\PostController;
use App\Controllers\TagController;
use App\Controllers\UserController;
use App\Models\User;
use App\Models\Post;

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

    public function update() {
        $model = new $this->model;
        $model->update($this->params);
    }

    public function delete() {
        $model = new $this->model;
        $model->delete($this->params);
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

    public static function redirect($url='') {
      header("Location: " . $url ,true,303);
      exit;
    }


}

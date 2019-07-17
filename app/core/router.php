<?php

namespace App\Core;

use App\Core\Controller;
use App\Controllers\AdminController;
use App\Controllers\CategoryController;
use App\Controllers\CommentController;
use App\Controllers\PostController;
use App\Controllers\TagController;
use App\Controllers\UserController;

/**
 * Router class
 */
class Router {
  protected $controller = 'PostController';
  protected $action = 'index';
  protected $params = [];

  public function __construct() {
    $this->prepareURL();

    // $userco = new \App\Controllers\UserController;
    // var_dump($userco);

    if (file_exists(CONTROLLER. $this->controller.'.php')) {
       // $that = $this;
       echo '<br>'.$this->controller.'<br>';
       var_dump($this->controller);
       // die();
       $this->controller = new $this->controller($this->params);

       if (empty($this->controller)) {
         header("Location: /post/index",true,303);
         exit;
       }

       if (method_exists($this->controller,$this->action)) {
         call_user_func_array([$this->controller,$this->action],$this->params);
       } else {
         header("Location: /post/index",true,303);
         exit;
       }
     } else {
       header("Location: /post/index",true,303);
       exit;
     }
  }

  public static function controller($value='')
  {
      echo 123;
  }

  protected function prepareURL() {
    $request = trim( $_SERVER['REQUEST_URI'],'/' );

    if (!empty( $request )) {
      $url = explode( '/',$request );
      $this->controller = isset( $url[0]) ? ucfirst($url[0]).'Controller' : 'PostController';
      $this->action = isset( $url[1]) ? $url[1] : 'index';
      unset( $url[0],$url[1] );
      $this->params = !empty($url) ? array_values($url) : [];
    } else {
       header("Location: /post/index",true,303);
       exit;
    }

  }
}

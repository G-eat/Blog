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
  protected $controller_name = '';

  public function __construct() {
    $this->prepareURL();

    if (file_exists(dirname( __DIR__ ).'\controllers\\'. $this->controller_name.'.php')) {
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


  protected function prepareURL() {
    $request = trim( $_SERVER['REQUEST_URI'],'/' );

    if (!empty( $request )) {
      $url = explode( '/',$request );
      $this->controller_name = isset( $url[0]) ? ucfirst($url[0]).'Controller' : 'PostController';
      $this->controller = isset( $url[0]) ? '\App\Controllers\\'.ucfirst($url[0]).'Controller' : 'PostController';
      $this->action = isset( $url[1]) ? $url[1] : 'index';
      unset( $url[0],$url[1] );
      $this->params = !empty($url) ? array_values($url) : [];
    } else {
       header("Location: /post/index",true,303);
       exit;
    }

  }
}

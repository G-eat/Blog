<?php

/**
 * Router class
 */
class Router {
  protected $controller = 'UserController';
  protected $action = 'login';
  protected $params = [];

  public function __construct() {
    $this->prepareURL();

    if (file_exists(CONTROLLER. $this->controller.'.php')) {
      $this->controller = new $this->controller;
      if (empty($this->controller)) {
        echo 123;
      }
      if (method_exists($this->controller,$this->action)) {
        call_user_func_array([$this->controller,$this->action],$this->params);
      } else {
        header("Location: /user/login",true,303);
        exit;
      }
    } else {
      header("Location: /user/login",true,303);
      exit;
    }
  }

  protected function prepareURL() {
    $request = trim( $_SERVER['REQUEST_URI'],'/' );

    if (!empty( $request )) {
      $url = explode( '/',$request );
      $this->controller = isset( $url[0]) ? strtoupper($url[0]).'Controller' : 'UserController';
      $this->action = isset( $url[1]) ? $url[1] : 'index';
      unset( $url[0],$url[1] );
      $this->params = !empty($url) ? array_values($url) : [];
    } else {
       header("Location: /user/index",true,303);
       exit;
    }

  }
}
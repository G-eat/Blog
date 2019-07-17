<?php

namespace App\Core;

/**
 *  View
 */
class View {

  protected $file;
  protected $data;

  public function __construct($file,$data) {
    $this->file = $file;
    $this->data = $data;
  }

  public function render() {
    file_exists(dirname( __DIR__ ).'\views\\'.$this->file.'.php') ? include dirname( __DIR__ ).'\views\\'.$this->file.'.php':include dirname( __DIR__ ).'\views\\'.'error.php';
  }

}

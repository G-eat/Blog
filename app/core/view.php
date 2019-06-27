<?php

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
    file_exists(VIEW.$this->file.'.php') ? include VIEW.$this->file.'.php':include VIEW.'error.php';
  }

}

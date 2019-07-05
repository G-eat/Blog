<?php

/**
 * Post
 */
class PostController extends Controller {
    public function index($msg='') {
      $data = Database::select(['*'],['categories']);
      $this->view('post\index',[
        'categories' => $data
      ]);
      $this->view->render();
    }
}

?>

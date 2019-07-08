<?php
if (!isset($_SESSION['admin'])) {
  Controller::redirect('/post/index');
}
/**
 * Admin
 */
class AdminController extends Controller {
  public function index() {
    $this->view('admin\index',[]);
    $this->view->render();
  }

  public function categories() {
    $data = Database::select(['*'],['categories']);
    $this->view('admin\categories',[
      'categories' => $data
    ]);
    $this->view->render();
  }

  public function users() {
    $data = Database::select(['*'],['users']);
    $this->view('admin\users',[
      'users' => $data
    ]);
    $this->view->render();
  }

  public function articles() {
    $data = Database::select(['*'],['articles']);
    $this->view('admin\articles',[
      'articles' => $data
    ]);
    $this->view->render();
  }

}

?>

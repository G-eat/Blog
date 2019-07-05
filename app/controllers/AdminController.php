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

}

?>

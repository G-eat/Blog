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
    $data = Database::select(['*'],['articles'],null,null,['position']);
    $this->view('admin\articles',[
      'articles' => $data
    ]);
    $this->view->render();
  }

  public function position() {
      $positions = $_POST['positions'];

      $num = 1;

      foreach ($positions as $position) {
        Database::update(['articles'],[['position','=',"'".$num."'"]],[['id','=',"'".$position."'"]]);
        $num ++;
      }
  }

  public function publish() {
      $is_publish = $_POST['is_publish'];
      $id = $_POST['id'];
      Database::update(['articles'],[['is_published','=',"'".$is_publish."'"]],[['id','=',"'".$id."'"]]);
      Controller::redirect('/admin/articles');
  }

}

?>

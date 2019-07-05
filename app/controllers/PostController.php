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

    public function add() {
      if (!isset($_SESSION['admin'])) {
        Controller::redirect('/post/index');
      }else{
        if (isset($_POST['submit'])) {
          if ($_POST['add_category'] !== '') {
            Database::insert(['categories'],['name'],["'".$_POST['add_category']."'"]);
          }
          Controller::redirect('/post/index');
        }
      }
    }

    public function delete() {
      if (!isset($_SESSION['admin'])) {
        Controller::redirect('/post/index');
      }else{
        if ($_POST['category_id'] !== '') {
          Database::delete(['categories'],[['id','=',"'".$_POST['category_id']."'"]]);
        }
        Controller::redirect('/post/index');
      }
    }

    public function category($value='') {
      echo $value;
    }
}

?>

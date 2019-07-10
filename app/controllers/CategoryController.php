<?php
/**
 *
 */
class CategoryController extends Controller {

  public function add() {
    if (!isset($_SESSION['admin'])) {
      Controller::redirect('/post/index');
    }else{
      if (isset($_POST['submit'])) {
        if ($_POST['add_category'] !== '') {
          Database::insert(['categories'],['name'],["'".$_POST['add_category']."'"]);
        }
        Controller::redirect('/admin/categories');
      }
    }
  }

  public function delete() {
    if (!isset($_SESSION['admin'])) {
      Controller::redirect('/post/index');
    }else{
      if ($_POST['category_id'] !== '') {
        Database::delete(['categories'],[['id','=',"'".$_POST['category_id']."'"]]);
        Database::delete(['articles'],[['category','=',"'".$_POST['category_name']."'"]]);
      }
      Controller::redirect('/admin/categories');
    }
  }

  public function update($value='') {
    if (!isset($_SESSION['admin'])) {
      Controller::redirect('/post/index');
    } else{
      if (isset($value)) {
        $data = Database::select(['*'],['categories'],[['id'],['='],["'".$value."'"]]);
        $this->view('admin\update',[
          'value' => $data
        ]);
        $this->view->render();
      } else {
        Controller::redirect('/post/index');
      }
    }
  }

  public function updated() {
    if (!isset($_SESSION['admin'])) {
      Controller::redirect('/post/index');
    } else {
      $category_id = $_POST['category_id'];
      $category = $_POST['category'];

      $data = Database::update(['categories'],[['name','=',"'".$category."'"]],[['id','=',"'".$category_id."'"]]);
      Controller::redirect('/admin/categories');
    }
  }
}

?>

<?php

if (!isset($_SESSION['admin'])) {
  Controller::redirect('/post/index');
}

/**
 * Category
 */
class CategoryController extends Controller {
  public function __construct() {
      User::isSetRemmember_me();
  }

  public function add() {
      if (isset($_POST['submit'])) {
        if ($_POST['add_category'] !== '') {
          Category::insertCategory($_POST['add_category']);
        }
        Controller::redirect('/admin/categories');
      }
  }

  public function delete() {
      if ($_POST['category_id'] !== '') {
        Category::deleteCategory($_POST['category_id']);
        Category::updateArticlesCategoryName($_POST['category_name']);
      }
      Controller::redirect('/admin/categories');
  }

  public function update($value='') {
      if (isset($value)) {
        $data = Category::getCategoryNameById($value);

        $this->view('admin\update',[
          'value' => $data
        ]);
        $this->view->render();
      } else {
        Controller::redirect('/post/index');
      }
  }

  public function updated() {
      $category_id = $_POST['category_id'];
      $category = $_POST['category'];

      Category::updateCategory($category,$category_id);
      Controller::redirect('/admin/categories');
  }

}

?>

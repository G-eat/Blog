<?php

    if (!isset($_SESSION['admin'])) {
      Message::setMsg('You not authorized.','success');
      Controller::redirect('/post/index');
    }

/**
 * Category
 */
class CategoryController extends Controller {
    public function __construct($params = null) {
       User::isSetRemmember_me();

       $this->params = $params;
       $this->model = 'Category';
       parent::__construct($params);
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

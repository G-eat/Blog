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

}

?>

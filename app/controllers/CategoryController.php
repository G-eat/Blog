<?php

    if (!isset($_SESSION['admin'])) {
        $message = new Message();
        $message->setMsg('You not authorized.','success');
        Controller::redirect('/post/index');
    }

/**
 * Category
 */
class CategoryController extends Controller {
        public function __construct($params = null) {
           $user = new User();
           $user->isSetRemmember_me();

           $this->params = $params;
           $this->model = 'Category';
           parent::__construct($params);
        }

        public function update($value='') {
            $category = new Category();
            if (isset($value)) {
            $data = $category->getCategoryNameById($value);

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

<?php

if (!isset($_SESSION['admin'])) {
  Controller::redirect('/post/index');
}

/**
 * Admin
 */
class AdminController extends Controller {

      public function __construct($params = null) {
         $user = new User();
         $user->isSetRemmember_me();

         $this->params = $params;
         $this->model = 'Admin';
         parent::__construct($params);
      }

      public function index() {
        $this->view('admin\index',[]);
        $this->view->render();
      }

      public function categories() {
        $database = new Database();
        $data = $database->getAll('categories');

        $this->view('admin\categories',[
          'categories' => $data
        ]);
        $this->view->render();
      }

      public function users() {
        $database = new Database();
        $data = $database->getAll('users');

        $this->view('admin\users',[
          'users' => $data
        ]);
        $this->view->render();
      }

      public function articles() {
        $admin = new Admin();
        $data = $admin->getAllArticlesByPosition();

        $this->view('admin\articles',[
          'articles' => $data
        ]);
        $this->view->render();
      }

      public function position() {
          $admin = new Admin();
          $positions = $_POST['positions'];

          $num = 1;

          foreach ($positions as $position) {
            $admin->updateArticlesPosition($num,$position);
            $num ++;
          }
      }

      //accept post from admin and publish them
      // public function publish() {
      //     $is_publish = $_POST['is_publish'];
      //     $id = $_POST['id'];
      //
      //     Admin::updateArticlesIsPublished($is_publish,$id);
      //     Controller::redirect('/admin/articles');
      // }

      public function comments() {
        $database = new Database();
        $comments = $database->getAll('comments');

        $this->view('admin\comments',[
          'comments' => $comments
        ]);
        $this->view->render();
      }

      //accept comment from admin and publish them
      public function accept() {
          $admin = new Admin();
          $is_accepted = $_POST['is_accepted'];
          $id = $_POST['id'];

          Message::setMsg('You create task.','success');

          $admin->updateCommentIsAccepted($is_accepted,$id);
          Controller::redirect('/admin/comments');
      }

      public function post($id) {
          $data = $admin->getArticleById($id);

          Controller::redirect('/post/individual/'.$data[0]['slug']);
      }

      public function tags() {
          $database = new Database;
          $tags = $database->getAll('tags');

          $this->view('admin\tags',[
            'tags' => $tags
          ]);
          $this->view->render();
      }

}

?>

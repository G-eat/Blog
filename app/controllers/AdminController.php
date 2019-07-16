<?php

if (!isset($_SESSION['admin'])) {
  Controller::redirect('/post/index');
}

/**
 * Admin
 */
class AdminController extends Controller {

      public function __construct($params = null) {
         User::isSetRemmember_me();

         $this->params = $params;
         $this->model = 'Admin';
         parent::__construct($params);
      }

      public function index() {
        $this->view('admin\index',[]);
        $this->view->render();
      }

      public function categories() {
        $data = Database::getAll('categories');

        $this->view('admin\categories',[
          'categories' => $data
        ]);
        $this->view->render();
      }

      public function users() {
        $data = Database::getAll('users');

        $this->view('admin\users',[
          'users' => $data
        ]);
        $this->view->render();
      }

      public function articles() {
        $data = Admin::getAllArticlesByPosition();

        $this->view('admin\articles',[
          'articles' => $data
        ]);
        $this->view->render();
      }

      public function position() {
          $positions = $_POST['positions'];

          $num = 1;

          foreach ($positions as $position) {
            Admin::updateArticlesPosition($num,$position);
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
        $comments = Database::getAll('comments');

        $this->view('admin\comments',[
          'comments' => $comments
        ]);
        $this->view->render();
      }

      //accept comment from admin and publish them
      public function accept() {
          $is_accepted = $_POST['is_accepted'];
          $id = $_POST['id'];

          Message::setMsg('You create task.','success');

          Admin::updateCommentIsAccepted($is_accepted,$id);
          Controller::redirect('/admin/comments');
      }

      public function post($id) {
          $data = Admin::getArticleById($id);

          Controller::redirect('/post/individual/'.$data[0]['slug']);
      }

      public function tags() {
          $tags = Database::getAll('tags');

          $this->view('admin\tags',[
            'tags' => $tags
          ]);
          $this->view->render();
      }

}

?>

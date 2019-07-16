<?php
/**
 * Comment
 */
class CommentController extends Controller {
    public function __construct($params = null) {
       User::isSetRemmember_me();

       $this->params = $params;
       $this->model = 'Comment';
       parent::__construct($params);
    }

    public function delete($id='' , $slug='') {
        if ($id === '' || $slug === '') {
            Controller::redirect('/post/index');
        }

        $data = Comment::getAuthorOfPostById($id);

        if ((isset($_SESSION['user']) && $_SESSION['user'] === $data[0]['author']) || isset($_SESSION['admin'])) {
            Comment::deleteById($id);
            Controller::redirect('/post/individual/'.$slug);
        } else {
            Controller::redirect('/post/index');
        }
    }

}

?>

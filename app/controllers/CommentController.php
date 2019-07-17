<?php
/**
 * Comment
 */
class CommentController extends Controller {
    public function __construct($params = null) {
       $user = new User();
       $user->isSetRemmember_me();

       $this->params = $params;
       $this->model = 'Comment';
       parent::__construct($params);
    }

    public function delete($id='' , $slug='') {
        $comment = new Comment();
        if ($id === '' || $slug === '') {
            Controller::redirect('/post/index');
        }

        $data = $comment->getAuthorOfPostById($id);

        if ((isset($_SESSION['user']) && $_SESSION['user'] === $data[0]['author']) || isset($_SESSION['admin'])) {
            $comment->deleteById($id);
            Controller::redirect('/post/individual/'.$slug);
        } else {
            Controller::redirect('/post/index');
        }
    }

}

?>

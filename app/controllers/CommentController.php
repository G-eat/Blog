<?php
/**
 * Comment
 */
class CommentController extends Controller {
    public function __construct() {
       User::isSetRemmember_me();
    }

    public function add(){
        if (!isset($_SESSION['user'])) {
            Controller::redirect('/post/index');
        }

        if (isset($_POST['comment']) && (trim($_POST['comment']) !== '')) {
            $article_slug = $_POST['article_slug'];

            Comment::insertCommment($_POST['comment'],$_POST['author'],$_POST['article_id']);
            Controller::redirect('/post/individual/'.$article_slug);
        } else {
            Controller::redirect('/post/index');
        }
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

    public function update() {
        if (isset($_POST['submit']) && (trim($_POST['update_comment']) !== '')) {

            if (!isset($_SESSION['user']) || $_POST['author'] !== $_SESSION['user']) {
                Controller::redirect('/post/index');
            }

            Comment::updateAcceptedColumnWhereCommentIsUpdated($_POST['update_comment'],$_POST['update_id']);
            Controller::redirect('/post/individual/'.$_POST['comment_slug']);
        } else {
            Controller::redirect('/post/index');
        }
    }

}

?>

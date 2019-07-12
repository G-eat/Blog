<?php
/**
 * Comment
 */
class CommentController extends Controller {

    public function add(){
        if (!isset($_SESSION['user'])) {
            Controller::redirect('/post/index');
        }

        if (isset($_POST['comment']) && (trim($_POST['comment']) !== '')) {
            $article_id = $_POST['article_id'];
            $author = $_POST['author'];
            $comment = $_POST['comment'];
            $article_slug = $_POST['article_slug'];

            Database::insert(['comments'],['comment','author','article_id'],["'".$comment."'","'".$author."'","'".$article_id."'"]);
            Controller::redirect('/post/individual/'.$article_slug);
        } else {
            Controller::redirect('/post/index');
        }
    }

    public function delete($id='' , $slug='') {
        if ($id === '' || $slug === '') {
            Controller::redirect('/post/index');
        }
        $data = Database::select(['author'],['comments'],[['id','=',"'".$id."'"]]);
        if ((isset($_SESSION['user']) && $_SESSION['user'] === $data[0]['author']) || isset($_SESSION['admin'])) {
            Database::delete(['comments'],[['id','=',"'".$id."'"]]);
            Controller::redirect('/post/individual/'.$slug);
        } else {
            Controller::redirect('/post/index');
        }
    }

    public function update() {
        if (isset($_POST['submit']) && (trim($_POST['update_comment']) !== '')) {
            $comment_id = $_POST['update_id'];
            $author = $_POST['author'];
            $comment = $_POST['update_comment'];
            $slug = $_POST['comment_slug'];

            if (!isset($_SESSION['user']) || $author !== $_SESSION['user']) {
                Controller::redirect('/post/index');
            }

            Database::update(['comments'],[['comment','=',"'".$comment."'"],['accepted','=',"'pending'"]],[['id','=',"'".$comment_id."'"]]);
            Controller::redirect('/post/individual/'.$slug);
        } else {
            Controller::redirect('/post/index');
        }
    }

}

?>

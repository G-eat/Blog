<?php
/**
 * Comment
 */
class Comment {

    public function insertCommment($comment,$author,$article_id) {
        return Database::insert(['comments'],['comment','author','article_id'],["'".$comment."'","'".$author."'","'".$article_id."'"]);
    }

    public function getAuthorOfPostById($id) {
        return Database::select(['author'],['comments'],[['id','=',"'".$id."'"]]);
    }

    public function deleteById($id) {
        Message::setMsg("Your're deleted comment.",'error');
        return Database::delete(['comments'],[['id','=',"'".$id."'"]]);
    }

    public function updateAcceptedColumnWhereCommentIsUpdated($comment,$comment_id) {
        return Database::update(['comments'],[['comment','=',"'".$comment."'"],['accepted','=',"'pending'"]],[['id','=',"'".$comment_id."'"]]);
    }

    public function create() {
        $comment = new Comment();
        if (!isset($_SESSION['user'])) {
            Message::setMsg("You're not logIn.",'error');
            Controller::redirect('/post/index');
        }

        if (isset($_POST['comment']) && (trim($_POST['comment']) !== '')) {
            $article_slug = $_POST['article_slug'];


            $comment->insertCommment($_POST['comment'],$_POST['author'],$_POST['article_id']);

            Message::setMsg('You create the comment,now admin need to accept that.','success');

            Controller::redirect('/post/individual/'.$article_slug);
        } else {
            Message::setMsg('Empty Comment.','error');
            Controller::redirect('/post/index');
        }
    }

    public function update() {
        $comment = new Comment();
        if (isset($_POST['submit']) && (trim($_POST['update_comment']) !== '')) {

            if (!isset($_SESSION['user']) || $_POST['author'] !== $_SESSION['user']) {
                Controller::redirect('/post/index');
            }

            $comment->updateAcceptedColumnWhereCommentIsUpdated($_POST['update_comment'],$_POST['update_id']);

            Message::setMsg('You update the comment,now admin need to accept that.','success');

            Controller::redirect('/post/individual/'.$_POST['comment_slug']);
        } else {
            Message::setMsg('Empty Comment.','error');
            Controller::redirect('/post/index');
        }
    }

}

?>

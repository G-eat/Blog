<?php
/**
 * Comment
 */
class Comment extends Database {

    public function insertCommment($comment,$author,$article_id) {
        return Database::insert(['comments'],['comment','author','article_id'],["'".$comment."'","'".$author."'","'".$article_id."'"]);
    }

    public function getAuthorOfPostById($id) {
        return Database::select(['author'],['comments'],[['id','=',"'".$id."'"]]);
    }

    public function deleteById($id) {
        return Database::delete(['comments'],[['id','=',"'".$id."'"]]);
    }

    public function updateAcceptedColumnWhereCommentIsUpdated($comment,$comment_id) {
        return Database::update(['comments'],[['comment','=',"'".$comment."'"],['accepted','=',"'pending'"]],[['id','=',"'".$comment_id."'"]]);
    }

    public function create() {
        if (!isset($_SESSION['user'])) {
            Message::setMsg("You're not logIn.",'error');
            Controller::redirect('/post/index');
        }

        if (isset($_POST['comment']) && (trim($_POST['comment']) !== '')) {
            $article_slug = $_POST['article_slug'];


            Comment::insertCommment($_POST['comment'],$_POST['author'],$_POST['article_id']);

            Message::setMsg('You create the comment,now admin need to accept that.','success');

            Controller::redirect('/post/individual/'.$article_slug);
        } else {
            Message::setMsg('Empty Comment.','error');
            Controller::redirect('/post/index');
        }
    }

}

?>

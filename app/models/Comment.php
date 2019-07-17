<?php

namespace App\Models;

use App\Core\Controller;
use App\Database\Database;
use App\Core\Message;

/**
 * Comment
 */
class Comment {

    public function insertCommment($comment,$author,$article_id) {
        $database = new Database();
        return $database->insert(['comments'],['comment','author','article_id'],["'".$comment."'","'".$author."'","'".$article_id."'"]);
    }

    public function getAuthorOfPostById($id) {
        $database = new Database();
        return $database->select(['author'],['comments'],[['id','=',"'".$id."'"]]);
    }

    public function deleteById($id) {
        $database = new Database();
        $message = new Message();

        $message->setMsg("Your're deleted comment.",'error');

        return $database->delete(['comments'],[['id','=',"'".$id."'"]]);
    }

    public function updateAcceptedColumnWhereCommentIsUpdated($comment,$comment_id) {
        $database = new Database();
        return $database->update(['comments'],[['comment','=',"'".$comment."'"],['accepted','=',"'pending'"]],[['id','=',"'".$comment_id."'"]]);
    }

    public function create() {
        $message = new Message();

        if (!isset($_SESSION['user'])) {
            $message->setMsg("You're not logIn.",'error');
            Controller::redirect('/post/index');
        }

        if (isset($_POST['comment']) && (trim($_POST['comment']) !== '')) {
            $article_slug = $_POST['article_slug'];

            $this->insertCommment($_POST['comment'],$_POST['author'],$_POST['article_id']);

            $message->setMsg('You create the comment,now admin need to accept that.','success');
            Controller::redirect('/post/individual/'.$article_slug);
        } else {
            $message->setMsg('Empty Comment.','error');
            Controller::redirect('/post/index');
        }
    }

    public function update() {
        $message = new Message();

        if (isset($_POST['submit']) && (trim($_POST['update_comment']) !== '')) {

            if (!isset($_SESSION['user']) || $_POST['author'] !== $_SESSION['user']) {
                Controller::redirect('/post/index');
            }

            $this->updateAcceptedColumnWhereCommentIsUpdated($_POST['update_comment'],$_POST['update_id']);

            $message->setMsg('You update the comment,now admin need to accept that.','success');
            Controller::redirect('/post/individual/'.$_POST['comment_slug']);
        } else {
            $message->setMsg('Empty Comment.','error');
            Controller::redirect('/post/index');
        }
    }

}

?>

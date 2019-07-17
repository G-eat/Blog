<?php

namespace App\Models;

use App\Core\Controller;
use App\Database\Database;
use App\Core\Message;

if (!isset($_SESSION['admin'])) {
  Controller::redirect('/post/index');
}
/**
 * Admin
 */
class Admin {

    public function getAllArticlesByPosition() {
        $database = new Database();
        return $database->select(['*'],['articles'],null,null,['position']);
    }

    public function updateArticlesPosition($num,$position) {
        $database = new Database();
        return $database->update(['articles'],[['position','=',"'".$num."'"]],[['id','=',"'".$position."'"]]);
    }

    public function updateArticlesIsPublished($is_publish,$id) {
        $database = new Database();
        return $database->update(['articles'],[['is_published','=',"'".$is_publish."'"]],[['id','=',"'".$id."'"]]);
    }

    public function updateCommentIsAccepted($is_accepted,$id) {
        $database = new Database();
        return $database->update(['comments'],[['accepted','=',"'".$is_accepted."'"]],[['id','=',"'".$id."'"]]);
    }

    public function getArticleById($id) {
        $database = new Database();
        return $database->select(['*'],['articles'],[['id','=',"'".$id."'"]]);
    }

    public function publish() {
        $message = new Message();

        $is_publish = $_POST['is_publish'];
        $id = $_POST['id'];

        $message->setMsg('You create task.','success');

        $this->updateArticlesIsPublished($is_publish,$id);

        Controller::redirect('/admin/articles');
    }

    public function delete() {
        $database = new Database();
        $message = new Message();

        $id = $_POST['id'];

        $database->delete(['articles'],[['id','=',"'".$id."'"]]);

        $message->setMsg('You delete article.','error');

        Controller::redirect('/admin/articles');
    }

}

?>

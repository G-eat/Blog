<?php
if (!isset($_SESSION['admin'])) {
  Controller::redirect('/post/index');
}
/**
 * Admin
 */
class Admin {

    public function getAllArticlesByPosition() {
        return Database::select(['*'],['articles'],null,null,['position']);
    }

    public function updateArticlesPosition($num,$position) {
        return Database::update(['articles'],[['position','=',"'".$num."'"]],[['id','=',"'".$position."'"]]);
    }

    public function updateArticlesIsPublished($is_publish,$id) {
        return Database::update(['articles'],[['is_published','=',"'".$is_publish."'"]],[['id','=',"'".$id."'"]]);
    }

    public function updateCommentIsAccepted($is_accepted,$id) {
        return Database::update(['comments'],[['accepted','=',"'".$is_accepted."'"]],[['id','=',"'".$id."'"]]);
    }

    public function getArticleById($id) {
        return Database::select(['*'],['articles'],[['id','=',"'".$id."'"]]);
    }

    public function publish() {
        $is_publish = $_POST['is_publish'];
        $id = $_POST['id'];

        Message::setMsg('You create task.','success');

        Admin::updateArticlesIsPublished($is_publish,$id);
        Controller::redirect('/admin/articles');
    }

    public function delete() {
        $id = $_POST['id'];

        Message::setMsg('You delete article.','success');

        Admin::delete(['articles'],[['id','=',"'".$id."'"]]);
        Controller::redirect('/admin/articles');
    }

}

?>

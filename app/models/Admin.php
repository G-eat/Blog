<?php
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
        $admin = new Admin();
        $is_publish = $_POST['is_publish'];
        $id = $_POST['id'];

        Message::setMsg('You create task.','success');

        $admin->updateArticlesIsPublished($is_publish,$id);
        Controller::redirect('/admin/articles');
    }

    public function delete() {
        $database = new Database();
        $id = $_POST['id'];

        $database->delete(['articles'],[['id','=',"'".$id."'"]]);

        Message::setMsg('You delete article.','error');
        Controller::redirect('/admin/articles');
    }

}

?>

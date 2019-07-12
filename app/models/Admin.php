<?php
/**
 * Admin
 */
class Admin extends Database {

    public function getAll($table) {
        return Database::select(['*'],[$table]);
    }

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

}

?>

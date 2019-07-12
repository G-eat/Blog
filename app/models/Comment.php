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

}

?>

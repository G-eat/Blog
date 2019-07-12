<?php
/**
 * Tag
 */
class Tag extends Database {

    public function insertTag($tag) {
        return Database::insert(['tags'],['name'],["'#".$tag."'"]);
    }

    public function updateTagNameInArticlesTagTable($tag_name) {
         return Database::update(['articles_tag'],[['tag_name','=',"'empty'"]],[['tag_name','=',"'".$tag_name."'"]]);
    }

    public function deleteTag($tag_id) {
        return Database::delete(['tags'],[['id','=',"'".$tag_id."'"]]);
    }

}

?>

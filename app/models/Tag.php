<?php
/**
 * Tag
 */
class Tag {

    public function insertTag($tag) {
        return Database::insert(['tags'],['name'],["'#".$tag."'"]);
    }

    public function updateTagNameInArticlesTagTable($tag_name) {
         return Database::update(['articles_tag'],[['tag_name','=',"'empty'"]],[['tag_name','=',"'".$tag_name."'"]]);
    }

    public function deleteTag($tag_id) {
        return Database::delete(['tags'],[['id','=',"'".$tag_id."'"]]);
    }

    public function create() {
        if (!isset($_SESSION['admin'])) {
          Message::setMsg("Your're not unauthorized.",'error');
          Controller::redirect('/post/index');
        } else {
          if (isset($_POST['submit'])) {
            if ($_POST['add_tag'] !== '') {
              Message::setMsg("You create new tag.",'success');
              Tag::insertTag($_POST['add_tag']);
            }
            Controller::redirect('/admin/tags');
          }
        }
    }

    public function delete() {
      if (!isset($_SESSION['admin'])) {
        Controller::redirect('/post/index');
      } else {
        if ($_POST['tag_name'] !== '') {
          Message::setMsg("Your're deleted tag.",'error');
          Tag::updateTagNameInArticlesTagTable($_POST['tag_name']);
          Tag::deleteTag($_POST['tag_id']);
        }
        Controller::redirect('/admin/tags');
      }
    }

}

?>

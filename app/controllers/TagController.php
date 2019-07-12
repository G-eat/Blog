<?php
/**
 * Tag
 */
class TagController extends Controller {
  public function __construct() {
     User::isSetRemmember_me();
  }

  public function add() {
    if (!isset($_SESSION['admin'])) {
      Controller::redirect('/post/index');
    } else {
      if (isset($_POST['submit'])) {
        if ($_POST['add_tag'] !== '') {
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
        Tag::updateTagNameInArticlesTagTable($_POST['tag_name']);
        Tag::deleteTag($_POST['tag_id']);
      }
      Controller::redirect('/admin/tags');
    }
  }

}

?>

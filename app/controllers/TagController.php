<?php
/**
 * Tag
 */
class TagController extends Controller {

  public function add() {
    if (!isset($_SESSION['admin'])) {
      Controller::redirect('/post/index');
    }else{
      if (isset($_POST['submit'])) {
        if ($_POST['add_tag'] !== '') {
          Database::insert(['tags'],['name'],["'#".$_POST['add_tag']."'"]);
        }
        Controller::redirect('/admin/tags');
      }
    }
  }

  public function delete() {
    if (!isset($_SESSION['admin'])) {
      Controller::redirect('/post/index');
    }else{
      if ($_POST['tag_name'] !== '') {
        Database::update(['articles_tag'],[['tag_name','=',"'empty'"]],[['tag_name','=',"'".$_POST['tag_name']."'"]]);
        Database::delete(['tags'],[['id','=',"'".$_POST['tag_id']."'"]]);
      }
      Controller::redirect('/admin/tags');
    }
  }

}

?>

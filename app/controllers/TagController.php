<?php
/**
 * Tag
 */
class TagController extends Controller {
    public function __construct($params = null) {
       User::isSetRemmember_me();

       $this->params = $params;
       $this->model = 'Tag';
       parent::__construct($params);
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

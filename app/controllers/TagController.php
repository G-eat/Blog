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
}

?>

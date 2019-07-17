<?php

namespace App\Controllers;

use App\Models\User;

/**
 * Tag
 */
class TagController extends Controller {
    public function __construct($params = null) {
       $user = new User();

       $user->isSetRemmember_me();

       $this->params = $params;
       $this->model = 'Tag';
       parent::__construct($params);
    }
}

?>

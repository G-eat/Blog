<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Comment;

/**
 * Comment
 */
class CommentController extends Controller {
    public function __construct($params = null) {
       $user = new User();

       $user->isSetRemmember_me();

       $this->params = $params;
       $this->model = 'App\Models\Comment';
       parent::__construct($params);
    }

    public function delete($id='' , $slug='') {
        $comment = new Comment();

        if ($id === '' || $slug === '') {
            Controller::redirect('/post/index');
        }

        $data = $comment->getAuthorOfPostById($id);

        if ((isset($_SESSION['user']) && $_SESSION['user'] === $data[0]['author']) || isset($_SESSION['admin'])) {
            $comment->deleteById($id);

            Controller::redirect('/post/individual/'.$slug);
        } else {
            Controller::redirect('/post/index');
        }
    }

}

?>

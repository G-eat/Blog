<?php

namespace App\Models;

use App\Core\Controller;
use App\Database\Database;
use App\Core\Message;

/**
 * Tag
 */
class Tag {

    public function insertTag($tag) {
        $database = new Database();
        return $database->insert(['tags'],['name'],["'#".$tag."'"]);
    }

    public function updateTagNameInArticlesTagTable($tag_name) {
        $database = new Database();
        return $database->update(['articles_tag'],[['tag_name','=',"'empty'"]],[['tag_name','=',"'".$tag_name."'"]]);
    }

    public function deleteTag($tag_id) {
        $database = new Database();
        return $database->delete(['tags'],[['id','=',"'".$tag_id."'"]]);
    }

    public function create() {
        $message = new Message();

        if (!isset($_SESSION['admin'])) {
          $message->setMsg("Your're not unauthorized.",'error');
          Controller::redirect('/post/index');
        } else {
          if (isset($_POST['submit'])) {
            if ($_POST['add_tag'] !== '') {
              $message->setMsg("You create new tag.",'success');
              $this->insertTag($_POST['add_tag']);
            }
            Controller::redirect('/admin/tags');
          }
        }
    }

    public function delete() {
        $message = new Message();

        if (!isset($_SESSION['admin'])) {
          Controller::redirect('/post/index');
        } else {
          if ($_POST['tag_name'] !== '') {
            $this->updateTagNameInArticlesTagTable($_POST['tag_name']);
            $this->deleteTag($_POST['tag_id']);

            $message->setMsg("Your're deleted tag.",'error');
          }
          Controller::redirect('/admin/tags');
        }
    }

}

?>

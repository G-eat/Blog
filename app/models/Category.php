<?php

namespace App\Models;

use App\Core\Controller;
use App\Database\Database;
use App\Core\Message;

/**
 * Category
 */
class Category {

    public function insertCategory($category) {
        $database = new Database();
        return $database->insert(['categories'],['name'],["'".$category."'"]);
    }

    public function deleteCategory($id) {
        $database = new Database();
        return $database->delete(['categories'],[['id','=',"'".$id."'"]]);
    }

    public function updateArticlesCategoryName($name) {
        $database = new Database();
        return $database->update(['articles'],[['category','=','null']],[['category','=',"'".$name."'"]]);
    }

    public function getCategoryNameById($value) {
        $database = new Database();
        return $database->select(['*'],['categories'],[['id'],['='],["'".$value."'"]]);
    }

    public function updateCategory($category,$category_id) {
        $database = new Database();
        return $database->update(['categories'],[['name','=',"'".$category."'"]],[['id','=',"'".$category_id."'"]]);
    }

    public function create() {
        $message = new Message();

        if (isset($_POST['submit'])) {
          if ($_POST['add_category'] !== '') {
            $message->setMsg("Your're created category.",'success');
            $this->insertCategory($_POST['add_category']);
          }
          Controller::redirect('/admin/categories');
        }
    }

    public function update() {
        $message = new Message();

        $category_id = $_POST['category_id'];
        $category = $_POST['category'];

        $this->updateCategory($category,$category_id);

        $message->setMsg("Your're updated category.",'success');
        Controller::redirect('/admin/categories');
    }

    public function delete() {
        $message = new Message();

        if ($_POST['category_id'] !== '') {
          $this->deleteCategory($_POST['category_id']);
          $message->setMsg("Your're deleted category.",'error');
          $this->updateArticlesCategoryName($_POST['category_name']);
        }
        Controller::redirect('/admin/categories');
    }

}

?>

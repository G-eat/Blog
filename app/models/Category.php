<?php
/**
 * Category
 */
class Category {

    public function insertCategory($category) {
        return Database::insert(['categories'],['name'],["'".$category."'"]);
    }

    public function deleteCategory($id) {
        return Database::delete(['categories'],[['id','=',"'".$id."'"]]);
    }

    public function updateArticlesCategoryName($name) {
        return Database::update(['articles'],[['category','=','null']],[['category','=',"'".$name."'"]]);
    }

    public function getCategoryNameById($value) {
        return Database::select(['*'],['categories'],[['id'],['='],["'".$value."'"]]);
    }

    public function updateCategory($category,$category_id) {
        return Database::update(['categories'],[['name','=',"'".$category."'"]],[['id','=',"'".$category_id."'"]]);
    }

    public function create() {
        $category = new Category();
        if (isset($_POST['submit'])) {
          if ($_POST['add_category'] !== '') {
            Message::setMsg("Your're created category.",'success');
            $category->insertCategory($_POST['add_category']);
          }
          Controller::redirect('/admin/categories');
        }
    }

    public function update() {
        $category = new Category();
        $category_id = $_POST['category_id'];
        $category = $_POST['category'];

        $category->updateCategory($category,$category_id);
        Message::setMsg("Your're updated category.",'success');
        Controller::redirect('/admin/categories');
    }

    public function delete() {
        $category = new Category();
        if ($_POST['category_id'] !== '') {
          $category->deleteCategory($_POST['category_id']);
          Message::setMsg("Your're deleted category.",'error');
          $category->updateArticlesCategoryName($_POST['category_name']);
        }
        Controller::redirect('/admin/categories');
    }

}

?>

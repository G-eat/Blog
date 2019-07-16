<?php
/**
 * Category
 */
class Category extends Database {

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
        if (isset($_POST['submit'])) {
          if ($_POST['add_category'] !== '') {
            Category::insertCategory($_POST['add_category']);
          }
          Controller::redirect('/admin/categories');
        }
    }

}

?>

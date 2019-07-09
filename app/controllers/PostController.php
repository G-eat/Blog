<?php
/**
 * Post
 */
class PostController extends Controller {

    public function index($id='') {
      if ($id == '' || $id == 1) {
         $limit_from = 0;
     } else {
         $limit_from = ($id - 1) * 5;
     }
     if ($id =='') {
        $id = 1;
     }
      $categories = Database::select(['*'],['categories']);
      $articles = Database::select(['*'],['articles'],[['is_published','=','"Publish"']],null,null,[$limit_from,'5']);
      $all_articles = Database::select(['*'],['articles'],[['is_published','=','"Publish"']]);
      $nr_page = ceil(count($all_articles)/5);

      if ($articles == null) {
         $error = 'Error! There are no link like this.';
     } else {
         $error = '';
     }
      $this->view('post\index',[
        'categories' => $categories,
        'articles' => $articles,
        'error' => $error,
        'nr_page' => $nr_page,
        'page_current' => $id
      ]);
      $this->view->render();
    }

    public function createpost($msg='') {
      if (isset($_POST['submit'])) {
        $slug = "'".Post::slug($_POST['slug'])."'";
        $author = $_SESSION['user'];
        $title = $_POST['title'];
        $body = $_POST['body-editor1'];
        $category = $_POST['category'];
        $image = uniqid('', true) . '-' .$_FILES['image']['name'];

        $mysql = 'SELECT COUNT(*) FROM `articles` WHERE `slug` = '.$slug;
        $data = Database::raw($mysql);

        if ($data[0] == 1) {
          $errrors = 'This slug is not available.';
          $categories = Database::select(['*'],['categories']);
          $this->view('post\createpost',[
            'msg' => $msg,
            'categories' => $categories,
            'errrors' => $errrors,
            'title' => $title,
            'category' => $category,
            'slug' => Post::slug($_POST['slug']),
            'body' => $body
          ]);
          $this->view->render();
        } else {
          $file_destination = '.\postPhoto\\'.$image;
          move_uploaded_file($_FILES['image']['tmp_name'],$file_destination);
          // var_dump($file_destination);


          Database::insert(['articles'],['author','title','body','slug','category','file_name'],[
            "'".$author."'","'".$title."'","'".$body."'",$slug,"'".$category."'","'".$image."'" ]);
          Controller::redirect('/post/index');
        }
      } else {
        $categories = Database::select(['*'],['categories']);
        $this->view('post\createpost',[
          'msg' => $msg,
          'categories' => $categories
        ]);
        $this->view->render();
      }
    }

    public function individual($slug) {
      $article = Database::select(['*'],['articles'],[['slug','=',"'".$slug."'"]]);
      $article_ispublish = Database::select(['*'],['articles'],[['slug','=',"'".$slug."'"],['AND'],['is_published','=','"Publish"']]);
      if (count($article_ispublish) == 0 && $article[0]['author'] !== $_SESSION['user']) {
          Controller::redirect('/post/index');
      }
      $author_articles = Database::select(['*'],['articles'],[['author','=',"'".$article[0]['author']."'"]]);
      $this->view('post\individual',[
        'article' => $article,
        'page' => 'Individual',
        'author_articles' => $author_articles
      ]);
      $this->view->render();
    }

    public function user($name) {
        if (isset($_SESSION['user']) && $name == $_SESSION['user']) {
            $articles = Database::select(['*'],['articles'],[['author','=',"'".$name."'"]]);
        } else {
            $articles = Database::select(['*'],['articles'],[['author','=',"'".$name."'"],['AND'],['is_published','=','"Publish"']]);
        }
        $this->view('post\user',[
          'articles' => $articles,
          'author' => $name
        ]);
        $this->view->render();
    }

    public function category($category) {
      $articles = Database::select(['*'],['articles'],[['category','=',"'".$category."'"]]);
      if (count($articles)) {
          $category_articles = Database::select(['*'],['articles'],[['category','=',"'".$articles[0]['category']."'"]]);
          $this->view('post\category',[
            'articles' => $articles,
            'category_articles' => $category_articles,
            'category' => $category
          ]);
          $this->view->render();
      } else {
          $this->view('post\category',[
              'articles' => $articles,
              'category' => $category
          ]);
          $this->view->render();
      }
    }

    public function search($msg='') {
        $search = $_POST['search'];
        if ($_POST['search'] == '') {
            Controller::redirect('/post/index');
        }
        if ($msg !== '' || !isset($_POST['search'])) {
            Controller::redirect('/post/index');
        }
        $articles = Database::select(['*'],['articles'],[['title','LIKE','"%'.$search.'%"'],['AND'],['is_published','=','"Publish"']]);
        $this->view('post\search',[
            'articles' => $articles,
            'search' => $search
        ]);
        $this->view->render();
    }

    // public function addpost() {
    //   $slug = "'".Post::slug($_POST['slug'])."'";
    //   $mysql = 'SELECT COUNT(*) FROM `articles` WHERE `slug` = '.$slug;
    //   $data = Database::raw($mysql);
    //   var_dump($data[0]);
    //   // var_dump($mysql);
    //
    //   if ($data[0] == 1) {
    //     Controller::redirect('/post/createpost/error');
    //   }
    //
    //   $author = $_SESSION['user'];
    //   $title = $_POST['title'];
    //   $body = $_POST['body-editor1'];
    //   $category = $_POST['category'];
    //   $image = $_FILES['image']['name'];
    //   Database::insert(['articles'],['author','title','body','slug','category','file_name'],[
    //     "'".$author."'","'".$title."'","'".$body."'",$slug,"'".$category."'","'".$image."'"
    //   ]);
    //   Controller::redirect('/post/index');
    // }

}

?>

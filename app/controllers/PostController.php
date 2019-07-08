<?php
/**
 * Post
 */
class PostController extends Controller {

    public function index($msg='') {
      $categories = Database::select(['*'],['categories']);
      $articles = Database::select(['*'],['articles']);
      $this->view('post\index',[
        'categories' => $categories,
        'articles' => $articles
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
      $this->view('post\individual',[
        'article' => $article,
        'page' => 'Individual'
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

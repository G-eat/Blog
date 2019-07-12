<?php
/**
 * Post
 */
class PostController extends Controller {

    public function index($order = '' , $id='') {
      User::isSetRemmember_me();

      if ($id == '' || $id == 1) {
         $limit_from = 0;
     } else {
         $limit_from = ($id - 1) * 5;
     }

     if ($id =='') {
       $id = 1;
    }

     if (isset($_POST['created_at'])) {
         Controller::redirect('/post/index/created_at');
     }

     if ($order === '') {
        Controller::redirect('/post/index/position');
     }

     if ($order === 'created_at') {
         $order = 'created_at';
         $by = 'DESC';
     } else {
         $order = 'position';
         $by = 'ASC';
     }


      $categories = Database::select(['*'],['categories']);
      $articles = Database::select(['*'],['articles'],[['is_published','=','"Publish"']],null,[$order,$by],[$limit_from,'5']);
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
        'page_current' => $id,
        'order' => $order
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
          $tags = Database::select(['*'],['tags']);

          $this->view('post\createpost',[
            'msg' => $msg,
            'categories' => $categories,
            'tags' => $tags,
            'errrors' => $errrors,
            'title' => $title,
            'category' => $category,
            'slug' => Post::slug($_POST['slug']),
            'body' => $body,
            'page' => 'CreatePost'
          ]);
          $this->view->render();
        } else {
          $file_destination = '.\postPhoto\\'.$image;
          move_uploaded_file($_FILES['image']['tmp_name'],$file_destination);
          // var_dump($file_destination);
          foreach ($_POST['tags'] as $tags) {
             Database::insert(['articles_tag'],['tag_name','article_slug'],["'".$tags."'",$slug]);
          }

          Database::insert(['articles'],['author','title','body','slug','category','file_name'],[
            "'".$author."'","'".$title."'","'".$body."'",$slug,"'".$category."'","'".$image."'" ]);
          Controller::redirect('/post/index');
        }
      } else {
        $categories = Database::select(['*'],['categories']);
        $tags = Database::select(['*'],['tags']);

        $this->view('post\createpost',[
          'msg' => $msg,
          'categories' => $categories,
          'tags' => $tags,
          'page' => 'CreatePost'
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

      $author_articles = Database::select(['*'],['articles'],[['author','=',"'".$article[0]['author']."'"],['AND'],['is_published','=','"Publish"']]);
      $tags = Database::select(['*'],['articles_tag'],[['article_slug','=',"'".$slug."'"]]);
      $comments = Database::select(['*'],['comments'],[['article_id','=',"'".$article[0]['id']."'"],['AND'],['accepted','=','"Accepted"']]);

      $this->view('post\individual',[
        'article' => $article,
        'page' => 'Individual',
        'tags' => $tags,
        'comments' => $comments,
        'author_articles' => $author_articles
      ]);
      $this->view->render();
    }

    public function user($name , $id = '') {
        if ($id == '' || $id == 1) {
           $limit_from = 0;
        } else {
           $limit_from = ($id - 1) * 5;
        }

        if ($id =='') {
           $id = 1;
        }

        if (isset($_SESSION['user']) && $name == $_SESSION['user']) {
            $articles = Database::select(['*'],['articles'],[['author','=',"'".$name."'"]],null,null,[$limit_from,'5']);
            $all_articles = Database::select(['*'],['articles'],[['author','=',"'".$name."'"]]);
            $nr_page = ceil(count($all_articles)/5);
        } else {
            $articles = Database::select(['*'],['articles'],[['author','=',"'".$name."'"],['AND'],['is_published','=','"Publish"']],null,null,[$limit_from,'5']);
            $all_articles = Database::select(['*'],['articles'],[['author','=',"'".$name."'"],['AND'],['is_published','=','"Publish"']]);
            $nr_page = ceil(count($all_articles)/5);
        }

        if ($id > $nr_page) {
            Controller::redirect('/post/user/'.$name);
        }

        $this->view('post\user',[
          'articles' => $articles,
          'author' => $name,
          'nr_page' => $nr_page,
          'page_current' => $id
        ]);
        $this->view->render();
    }

    public function category($category) {
      $articles = Database::select(['*'],['articles'],[['category','=',"'".$category."'"],['AND'],['is_published','=',"'Publish'"]]);
      $categories = Database::select(['*'],['categories']);

      if (count($articles)) {
          $category_articles = Database::select(['*'],['articles'],[['category','=',"'".$articles[0]['category']."'"],['AND'],['is_published','=',"'Publish'"]]);

          $this->view('post\category',[
            'articles' => $articles,
            'category_articles' => $category_articles,
            'category' => $category,
            'categories' => $categories
          ]);
          $this->view->render();
      } else {
          $this->view('post\category',[
              'articles' => $articles,
              'category' => $category,
              'categories' => $categories
          ]);
          $this->view->render();
      }
    }

    public function search($search='', $id = '') {
        if ($search !== '' && !isset($_POST['search'])) {
            $search = $search;
        } elseif (!isset($_POST['search']) || $_POST['search'] == '') {
            Controller::redirect('/post/index');
        } else {
            $search = $_POST['search'];
        }

        if ($id == '' || $id == 1) {
           $limit_from = 0;
        } else {
           $limit_from = ($id - 1) * 1;
        }

        if ($id =='') {
          $id = 1;
        }


        $articles = Database::select(['*'],['articles'],[['title','LIKE','"%'.$search.'%"'],['AND'],['is_published','=','"Publish"']],null,null,[$limit_from,'1']);
        $all_articles = Database::select(['*'],['articles'],[['title','LIKE','"%'.$search.'%"'],['AND'],['is_published','=','"Publish"']]);
        $nr_page = ceil(count($all_articles)/1);

        if ($id > $nr_page) {
            Controller::redirect('/post/search/'.$search.'/1');
        }

        $this->view('post\search',[
            'articles' => $articles,
            'search' => $search,
            'nr_page' => $nr_page,
            'page_current' => $id,
        ]);
        $this->view->render();
    }

    public function tag($value='') {
        $tag = '#'.$value;
        $datas = Database::select(['*'],['articles_tag'],[['tag_name','=',"'".$tag."'"]]);

        $articles_id = array();
        foreach ($datas as $data) {
            array_push($articles_id,Database::select(['id'],['articles'],[['slug','=',"'".$data['article_slug']."'"]]));
        }

        $articles = array();
        for ($i=0; $i < count($articles_id); $i++) {
            $data = Database::select(['*'],['articles'],[['id'.'=',"'".$articles_id[$i][0]['id']."'"]]);
            array_push($articles,$data);
        }

        $categories = Database::select(['*'],['categories']);

        $this->view('post\tag',[
            'articles' => $articles,
            'categories' => $categories,
            'tag' => $tag
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
